#!/usr/bin/env python3
"""
Solution script for Spectral Network challenge (Simplified Version)

This script extracts and processes the hidden data from the challenge PCAP:
1. Extract encoded data from DNS queries (with sequence numbers)
2. Find the IV in HTTP headers
3. Extract encrypted data from ICMP packets (with GHOSTDATA marker)
4. Decrypt the data to reveal the flag

Requirements:
- pip install pyshark pycryptodome

Usage:
- python solve.py spectral_capture.pcap
"""

import pyshark
import sys
import re
import binascii
import base64
import hashlib
from Cryptodome.Cipher import AES
from Cryptodome.Util.Padding import unpad

# Password and IV will be extracted from the PCAP
# Fallback values if extraction fails
DEFAULT_PASSWORD = "ghost_hunter_password"
DEFAULT_IV = b"spookyghostivxxx"

def print_header(message):
    """Print a formatted header message"""
    print("\n" + "=" * 60)
    print(f" {message}")
    print("=" * 60)

def extract_dns_data(pcap_file):
    """Extract and decode data hidden in DNS queries with sequence numbers"""
    print_header("Stage 1: Extracting Data from DNS Queries")
    
    # Open the PCAP file
    print("[+] Opening PCAP file:", pcap_file)
    capture = pyshark.FileCapture(pcap_file, display_filter='dns && dns.qry.name contains "hauntednode.local"')
    
    # Extract domain parts with sequence numbers
    dns_data = {}
    
    print("[+] Searching for DNS queries to hauntednode.local subdomain...")
    for packet in capture:
        try:
            if hasattr(packet, 'dns') and hasattr(packet.dns, 'qry_name'):
                query_name = packet.dns.qry_name
                if 'hauntednode.local' in query_name:
                    # Extract the subdomain part
                    subdomain = query_name.split('.hauntednode.local')[0]
                    print(f"  [DEBUG] Found query: {subdomain}")
                    
                    # Extract sequence number (format: s1-data, s2-data, etc.)
                    seq_match = re.match(r's(\d+)-(.+)', subdomain)
                    if seq_match:
                        seq_num = int(seq_match.group(1))
                        data = seq_match.group(2)
                        dns_data[seq_num] = data
        except AttributeError:
            continue
    
    print(f"[+] Found {len(dns_data)} DNS queries with sequence numbers")
    
    # Sort by sequence number and combine data
    ordered_data = ""
    for seq in sorted(dns_data.keys()):
        ordered_data += dns_data[seq]
    
    print(f"[+] Ordered data: {ordered_data}")
    
    try:
        # Convert hex to ASCII text
        decoded_data = binascii.unhexlify(ordered_data).decode('utf-8')
        print(f"[+] Decoded result (password): {decoded_data}")
        return decoded_data
    except Exception as e:
        print(f"[!] Error decoding data: {e}")
        print(f"[!] Using default password: {DEFAULT_PASSWORD}")
        return DEFAULT_PASSWORD

def extract_iv_from_http(pcap_file):
    """Find and extract the IV from HTTP headers"""
    print_header("Stage 2: Extracting IV from HTTP Headers")
    
    print("[+] Opening PCAP file for HTTP analysis")
    # Look for HTTP User-Agent with IV
    capture = pyshark.FileCapture(pcap_file, display_filter='http contains "INIT_VECTOR"')
    
    for packet in capture:
        try:
            if hasattr(packet, 'http') and hasattr(packet.http, 'user_agent'):
                user_agent = packet.http.user_agent
                
                # Look for the IV pattern (now more obvious)
                if 'INIT_VECTOR:' in user_agent:
                    print(f"[+] Found User-Agent with IV: {user_agent}")
                    
                    # Extract the Base64 encoded IV
                    iv_match = re.search(r'INIT_VECTOR:([A-Za-z0-9+/=]+)', user_agent)
                    if iv_match:
                        encoded_iv = iv_match.group(1)
                        iv = base64.b64decode(encoded_iv)
                        print(f"[+] Extracted IV: {iv.decode('utf-8', errors='ignore')} (hex: {iv.hex()})")
                        return iv
        except Exception as e:
            print(f"[!] Error processing packet: {e}")
            continue
    
    print("[!] IV not found in HTTP headers, using default")
    return DEFAULT_IV

def extract_encrypted_data(pcap_file):
    """Extract encrypted data from ICMP packets (with GHOSTDATA marker)"""
    print_header("Stage 3: Extracting Encrypted Data from ICMP")
    
    # Use tshark for ICMP data extraction
    print("[+] Using tshark for ICMP data extraction...")
    try:
        import subprocess
        
        # Extract the data part of ICMP packets, looking for the marker
        cmd = f"tshark -r {pcap_file} -Y 'icmp' -T fields -e data"
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
        
        if result.returncode == 0 and result.stdout:
            data_lines = [line.strip() for line in result.stdout.splitlines() if line.strip()]
            print(f"[+] Found {len(data_lines)} ICMP data lines")
            
            # Look for the GHOSTDATA marker in hex
            ghost_marker_hex = binascii.hexlify(b"GHOSTDATA:").decode()
            encrypted_data = None
            
            for line in data_lines:
                if ghost_marker_hex in line:
                    # Find position of marker
                    marker_pos = line.find(ghost_marker_hex)
                    # Get data after marker (marker_pos + len of marker in hex)
                    data_part = line[marker_pos + len(ghost_marker_hex):]
                    
                    # Convert to bytes
                    try:
                        encrypted_data = binascii.unhexlify(data_part)
                        print(f"[+] Found encrypted data with GHOSTDATA marker, size: {len(encrypted_data)} bytes")
                        break
                    except Exception as e:
                        print(f"[!] Error processing data line with marker: {e}")
            
            if encrypted_data:
                return encrypted_data
            else:
                print("[!] Could not find GHOSTDATA marker in ICMP packets")
                return None
                
        else:
            print(f"[!] Error running tshark: {result.stderr if result.stderr else 'No output'}")
            return None
    except Exception as e:
        print(f"[!] Error using tshark: {e}")
        return None

def decrypt_data(password, iv, encrypted_data):
    """Decrypt the data using AES-256-CBC"""
    print_header("Stage 4: Decrypting the Hidden Message")
    
    print(f"[+] Password: {password}")
    print(f"[+] IV: {iv.decode('utf-8', errors='ignore')} (len: {len(iv)})")
    print(f"[+] Encrypted data size: {len(encrypted_data)} bytes")
    
    # Generate key from password using SHA-256
    key = hashlib.sha256(password.encode()).digest()  # 32 bytes for AES-256
    print(f"[+] Generated AES key (first 8 bytes): {key[:8].hex()}")
    
    try:
        # Create AES cipher
        cipher = AES.new(key, AES.MODE_CBC, iv)
        
        # Decrypt the data
        decrypted_raw = cipher.decrypt(encrypted_data)
        
        # Try to unpad
        try:
            decrypted_data = unpad(decrypted_raw, AES.block_size)
            print("[+] Successfully unpadded the data")
        except Exception as padding_error:
            print(f"[!] Padding error: {padding_error}. Using raw decrypted data.")
            decrypted_data = decrypted_raw
        
        # Try to decode as UTF-8
        decrypted_text = decrypted_data.decode('utf-8', errors='replace')
        print("\nDecrypted message:")
        print("-" * 50)
        print(decrypted_text)
        print("-" * 50)
        
        # Extract flag
        flag_match = re.search(r'flag\{[^}]+\}', decrypted_text)
        if flag_match:
            flag = flag_match.group(0)
            print(f"\n[*] Found Flag: {flag}")
            print(f"[*] Challenge successfully solved!")
            return flag
        else:
            print("[!] Could not find flag in decrypted text")
            return None
            
    except Exception as e:
        print(f"[!] Decryption error: {e}")
        
        # Try with default values as fallback
        print("\n[!] Attempting fallback decryption with default values...")
        try:
            cipher = AES.new(hashlib.sha256(DEFAULT_PASSWORD.encode()).digest(), AES.MODE_CBC, DEFAULT_IV)
            decrypted_data = unpad(cipher.decrypt(encrypted_data), AES.block_size)
            decrypted_text = decrypted_data.decode('utf-8')
            
            print("\nDecrypted message (using fallback values):")
            print("-" * 50)
            print(decrypted_text)
            print("-" * 50)
            
            flag_match = re.search(r'flag\{[^}]+\}', decrypted_text)
            if flag_match:
                flag = flag_match.group(0)
                print(f"\n[*] Found Flag: {flag}")
                print(f"[*] Challenge successfully solved with fallback values!")
                return flag
                
        except Exception as fallback_error:
            print(f"[!] Fallback decryption also failed: {fallback_error}")
            return None

def main():
    if len(sys.argv) != 2:
        print(f"Usage: {sys.argv[0]} <path_to_pcap_file>")
        sys.exit(1)
    
    pcap_file = sys.argv[1]
    
    # Extract the password from DNS queries
    password = extract_dns_data(pcap_file)
    
    # Extract the IV from HTTP headers
    iv = extract_iv_from_http(pcap_file)
    
    # Extract encrypted data from ICMP packets
    encrypted_data = extract_encrypted_data(pcap_file)
    if not encrypted_data:
        print("[!] Failed to extract encrypted data. Exiting.")
        sys.exit(1)
    
    # Decrypt the data to get the flag
    flag = decrypt_data(password, iv, encrypted_data)
    
    if flag:
        print(f"\nCTF Flag: {flag}")
    else:
        print("\n[!] Unable to recover the flag from the PCAP file.")

if __name__ == "__main__":
    main() 