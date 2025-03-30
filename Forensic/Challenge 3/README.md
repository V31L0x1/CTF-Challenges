# Spectral Network

## Challenge Description
During a paranormal investigation at an abandoned server room, our team captured network traffic that appears to contain encrypted communications between an unknown entity and devices in the network. Can you analyze this network traffic and uncover the spectral message?

## Difficulty
Hard

## Category
Forensics

## Files Provided
- spectral_capture.pcap (Network traffic capture containing hidden data)

## Hints
1. Look for unusual protocol behavior in DNS queries, HTTP headers, and ICMP packets.
2. DNS queries often contain encoded information - check for sequence numbers.
3. HTTP headers might contain initialization vectors for decryption.
4. ICMP packets can carry payloads with encrypted data.

## Solution
1. Extract encoded data from DNS queries with sequence numbers (s1-, s2-, etc.) to hauntednode.local
   ```python
   # Extract data from DNS queries using a tool like tshark
   tshark -r spectral_capture.pcap -Y "dns.qry.name contains hauntednode.local" -T fields -e dns.qry.name
   ```

2. Reorder by sequence number, decode hex values to get the password:
   ```
   ghost_hunter_password
   ```

3. Find the IV in HTTP headers:
   ```python
   # Look for INIT_VECTOR: header in HTTP traffic
   tshark -r spectral_capture.pcap -Y "http contains INIT_VECTOR" -T fields -e http.header
   ```
   IV (after base64 decoding): `spookyghostivxxx`

4. Extract encrypted data from ICMP packets with the GHOSTDATA: marker:
   ```python
   # Extract data from ICMP packets
   tshark -r spectral_capture.pcap -Y "icmp contains GHOSTDATA" -T fields -e data
   ```

5. Decrypt using AES-256-CBC with the extracted password and IV:
   ```python
   from Crypto.Cipher import AES
   from Crypto.Util.Padding import unpad
   import base64
   
   password = "ghost_hunter_password"
   iv = b"spookyghostivxxx"
   key = hashlib.sha256(password.encode()).digest()
   cipher = AES.new(key, AES.MODE_CBC, iv)
   decrypted = unpad(cipher.decrypt(encrypted_data), AES.block_size)
   print(decrypted.decode())
   ```

## Flag
```
flag{sp3ctr4l_p4ck3t_4n4lys1s}
``` 