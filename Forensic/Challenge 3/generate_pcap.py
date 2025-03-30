#!/usr/bin/env python3
"""
Generate PCAP file for Spectral Network challenge - Simplified version

This script creates a packet capture file containing:
1. DNS queries with hidden encoded data (with sequence numbers)
2. HTTP traffic with a hidden encryption initialization vector
3. ICMP packets containing encrypted flag data (with simpler structure)
4. Decoy traffic to make analysis more challenging

Requirements:
- pip install scapy pycryptodome

Run:
- python generate_pcap.py
"""

from scapy.all import *
import random
import binascii
import os
import hashlib
import base64
from Cryptodome.Cipher import AES
from Cryptodome.Util.Padding import pad

# Challenge configuration - KEEP SIMPLE
PASSWORD = "ghost_hunter_password"  # Simpler password
IV = b"spookyghostivxxx"  # Exactly 16 bytes, easy to identify
FLAG = "flag{sp3ctr4l_p4ck3t_4n4lys1s}"  # Shorter flag
OUTPUT_PCAP = "spectral_capture.pcap"

# Hostnames and IPs for the challenge
DNS_SERVER = "8.8.8.8"
ATTACKER_IP = "192.168.1.66"
VICTIM_IP = "192.168.1.100"
STRANGE_IP = "192.168.1.200"  # For ICMP traffic
DOMAIN = "hauntednode.local"
WEB_SERVER = "hauntedserver.local"

def generate_dns_packets():
    """Generate DNS queries containing the hex-encoded password with sequence numbers"""
    print(f"[+] Encoding password: {PASSWORD}")
    
    # Convert password to hex
    hex_password = binascii.hexlify(PASSWORD.encode()).decode()
    print(f"[+] Hex-encoded password: {hex_password}")
    
    # Split into chunks and add sequence numbers for reliable ordering
    chunks = []
    chunk_size = 8  # 4 bytes per chunk
    for i in range(0, len(hex_password), chunk_size):
        chunk = hex_password[i:i+chunk_size]
        if chunk:
            # Add sequence number at the beginning: s1-, s2-, etc.
            seq_num = (i // chunk_size) + 1
            chunks.append(f"s{seq_num}-{chunk}")
    
    print(f"[+] Split into {len(chunks)} DNS queries with sequence numbers: {chunks}")
    
    # Create DNS query packets
    dns_packets = []
    src_port = random.randint(10000, 60000)
    
    # Randomize the order to make it a bit more challenging but still solvable
    shuffled_chunks = chunks.copy()
    random.shuffle(shuffled_chunks)
    
    for chunk in shuffled_chunks:
        # Randomize source port slightly for realism
        src_port = src_port + random.randint(1, 5)
        if src_port > 65000:
            src_port = random.randint(10000, 60000)
            
        dns_packets.append(
            IP(src=VICTIM_IP, dst=DNS_SERVER) /
            UDP(sport=src_port, dport=53) /
            DNS(
                rd=1,  # Recursion desired
                qd=DNSQR(qname=f"{chunk}.{DOMAIN}", qtype="A")
            )
        )
        
        # Add corresponding DNS response for realism
        dns_packets.append(
            IP(src=DNS_SERVER, dst=VICTIM_IP) /
            UDP(sport=53, dport=src_port) /
            DNS(
                id=dns_packets[-1][DNS].id,
                qr=1,  # Response
                aa=0,  # Not authoritative
                rd=1,  # Recursion desired
                ra=1,  # Recursion available
                qd=DNSQR(qname=f"{chunk}.{DOMAIN}", qtype="A"),
                an=DNSRR(
                    rrname=f"{chunk}.{DOMAIN}",
                    type="A",
                    ttl=300,
                    rdata="127.0.0.1"  # Localhost as response
                )
            )
        )
        
    return dns_packets

def generate_http_packets():
    """Generate HTTP traffic with the IV hidden in User-Agent - Made more obvious"""
    print(f"[+] Creating HTTP traffic with hidden IV")
    
    # Encode IV in base64
    encoded_iv = base64.b64encode(IV).decode()
    print(f"[+] IV: {IV.decode()}, Base64 encoded: {encoded_iv}")
    
    # Initial TCP handshake
    tcp_handshake = [
        IP(src=VICTIM_IP, dst=STRANGE_IP) / 
        TCP(sport=random.randint(10000, 60000), dport=80, flags="S"),
        
        IP(src=STRANGE_IP, dst=VICTIM_IP) /
        TCP(sport=80, dport=random.randint(10000, 60000), flags="SA"),
        
        IP(src=VICTIM_IP, dst=STRANGE_IP) /
        TCP(sport=random.randint(10000, 60000), dport=80, flags="A")
    ]
    
    # HTTP request with hidden IV in User-Agent - MAKE MORE OBVIOUS
    http_request = IP(src=VICTIM_IP, dst=STRANGE_IP) / TCP(sport=random.randint(10000, 60000), dport=80, flags="PA") / Raw(
        b"GET / HTTP/1.1\r\n"
        b"Host: " + WEB_SERVER.encode() + b"\r\n"
        b"User-Agent: Mozilla/5.0 INIT_VECTOR:" + encoded_iv.encode() + b" (Ghost Hunter)\r\n"
        b"Accept: text/html,application/xhtml+xml,application/xml\r\n"
        b"Connection: keep-alive\r\n\r\n"
    )
    
    # HTTP response mentioning AES - MORE OBVIOUS
    http_response = IP(src=STRANGE_IP, dst=VICTIM_IP) / TCP(sport=80, dport=random.randint(10000, 60000), flags="PA") / Raw(
        b"HTTP/1.1 200 OK\r\n"
        b"Server: HauntedServer/1.0\r\n"
        b"Content-Type: text/plain\r\n"
        b"Content-Length: 118\r\n"
        b"Connection: close\r\n\r\n"
        b"ENCRYPTION_DETAILS: Using AES-256-CBC for secure comms.\r\n"
        b"INIT_VECTOR is in the User-Agent header.\r\n"
        b"DATA_LOCATION: Look for secret data in ICMP packets."
    )
    
    return tcp_handshake + [http_request, http_response]

def generate_encrypted_icmp():
    """Generate ICMP packets containing the encrypted flag data - Simplified structure"""
    print(f"[+] Encrypting flag message with AES-256-CBC")
    
    # Create the message containing the flag - SIMPLER MESSAGE
    message = (
        f"Congratulations on finding the hidden message!\n"
        f"The flag is: {FLAG}"
    )
    
    # Generate key from password using SHA-256
    key = hashlib.sha256(PASSWORD.encode()).digest()  # 32 bytes for AES-256
    
    # Encrypt the message
    cipher = AES.new(key, AES.MODE_CBC, IV)
    encrypted_data = cipher.encrypt(pad(message.encode(), AES.block_size))
    
    print(f"[+] Encrypted {len(message)} bytes into {len(encrypted_data)} bytes")
    
    # Add a marker to make it easier to identify the encrypted data
    marked_data = b"GHOSTDATA:" + encrypted_data
    
    # Put all the data in a single ICMP packet for simplicity
    icmp_packets = [
        IP(src=ATTACKER_IP, dst=STRANGE_IP) / ICMP(type=8, code=0, seq=1) / Raw(marked_data)
    ]
    
    # Add a response for realism
    icmp_packets.append(
        IP(src=STRANGE_IP, dst=ATTACKER_IP) / ICMP(type=0, code=0, seq=1) / Raw(marked_data)
    )
    
    print(f"[+] Created ICMP packets with encrypted data")
    return icmp_packets

def generate_decoy_packets(num_decoys=50):
    """Generate decoy packets - Reduced complexity"""
    print(f"[+] Generating {num_decoys} decoy packets")
    
    decoy_packets = []
    
    # List of common ports
    common_ports = [80, 443, 8080, 22, 21, 25]
    
    # List of decoy IPs in the same subnet
    decoy_ips = [f"192.168.1.{i}" for i in range(1, 30) if i not in [66, 100, 200]]
    
    for _ in range(num_decoys):
        src_ip = random.choice(decoy_ips)
        dst_ip = random.choice(decoy_ips)
        
        # Ensure source and destination are different
        while src_ip == dst_ip:
            dst_ip = random.choice(decoy_ips)
        
        # Random port numbers
        sport = random.choice(common_ports) if random.random() < 0.5 else random.randint(1024, 65535)
        dport = random.choice(common_ports) if random.random() < 0.5 else random.randint(1024, 65535)
        
        # Generate various protocol packets (simpler mix)
        packet_type = random.random()
        
        if packet_type < 0.7:  # 70% TCP
            tcp_flags = random.choice(["S", "SA", "A", "PA"])
            packet = IP(src=src_ip, dst=dst_ip) / TCP(sport=sport, dport=dport, flags=tcp_flags)
            
        else:  # 30% UDP
            packet = IP(src=src_ip, dst=dst_ip) / UDP(sport=sport, dport=dport)
        
        decoy_packets.append(packet)
    
    return decoy_packets

def generate_http_decoy_traffic(num_conversations=20):
    """Generate HTTP decoy traffic - Reduced complexity"""
    print(f"[+] Generating {num_conversations} HTTP conversations")
    
    http_packets = []
    common_websites = [
        "example.com", "google.com", "github.com", 
        "ghosthunter.com", "paranormal.org"
    ]
    
    for _ in range(num_conversations):
        # Pick random client and server IPs
        client_ip = f"192.168.1.{random.randint(1, 50)}"
        server_ip = f"192.168.1.{random.randint(51, 254)}"
        
        # Client port and server port
        client_port = random.randint(49152, 65535)
        server_port = 80
        
        # Website
        website = random.choice(common_websites)
        
        # Simple HTTP conversation
        syn = IP(src=client_ip, dst=server_ip) / TCP(sport=client_port, dport=server_port, flags="S")
        syn_ack = IP(src=server_ip, dst=client_ip) / TCP(sport=server_port, dport=client_port, flags="SA")
        ack = IP(src=client_ip, dst=server_ip) / TCP(sport=client_port, dport=server_port, flags="A")
        
        http_request = IP(src=client_ip, dst=server_ip) / TCP(sport=client_port, dport=server_port, flags="PA") / Raw(
            f"GET / HTTP/1.1\r\nHost: {website}\r\nUser-Agent: Mozilla/5.0\r\n\r\n".encode()
        )
        
        http_response = IP(src=server_ip, dst=client_ip) / TCP(sport=server_port, dport=client_port, flags="PA") / Raw(
            b"HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nContent-Length: 100\r\n\r\n<html>Simple response</html>"
        )
        
        http_packets.extend([syn, syn_ack, ack, http_request, http_response])
    
    return http_packets

def generate_pcap():
    """Generate the complete PCAP file for the challenge - Simplified"""
    print(f"[*] Generating PCAP for Spectral Network challenge")
    
    # Get all packets for the challenge
    dns_packets = generate_dns_packets()
    http_packets = generate_http_packets()
    icmp_packets = generate_encrypted_icmp()
    
    # Generate decoy traffic (reduced amount)
    basic_decoy_packets = generate_decoy_packets(num_decoys=100)
    http_decoy_packets = generate_http_decoy_traffic(num_conversations=20)
    
    # Combine all packets
    all_packets = dns_packets + http_packets + icmp_packets + basic_decoy_packets + http_decoy_packets
    
    print(f"[+] Created a total of {len(all_packets)} packets")
    
    # Randomize the packet order but keep ICMP packets close together
    # Extract ICMP packets to keep them together
    challenge_icmp = icmp_packets
    other_packets = dns_packets + http_packets + basic_decoy_packets + http_decoy_packets
    random.shuffle(other_packets)
    
    # Insert ICMP packets in the middle somewhere
    insert_pos = len(other_packets) // 2
    final_packets = other_packets[:insert_pos] + challenge_icmp + other_packets[insert_pos:]
    
    # Add timestamps
    base_time = 1617235200  # April 1, 2021 00:00:00 UTC
    time_interval = 0.001  # 1ms between packets on average
    
    for i, packet in enumerate(final_packets):
        packet.time = base_time + (i * time_interval)
    
    # Write to PCAP
    wrpcap(OUTPUT_PCAP, final_packets)
    print(f"[*] Created PCAP file: {OUTPUT_PCAP} with {len(final_packets)} packets")
    print(f"[*] Flag: {FLAG}")
    
    # Print information needed for solving
    print("\nChallenge solution information:")
    print(f"Password: {PASSWORD}")
    print(f"IV: {IV.decode()}")
    print(f"Encrypted data is marked with 'GHOSTDATA:' in ICMP packets")

if __name__ == "__main__":
    generate_pcap() 