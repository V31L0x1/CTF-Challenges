# Digital Apparition

## Challenge Description
Our paranormal investigation team captured a memory dump from a computer in an abandoned security office. The system was mysteriously still running after years of being untouched. We believe a malicious entity has accessed this system and might have left some digital traces behind. Can you analyze the memory dump and uncover the password they used?

## Difficulty
Medium

## Category
Forensics

## Files Provided
- memory.dmp (Windows memory dump containing credentials)
- wordlist.txt (A list of possible passwords to try)

## Hints
1. Looking at the memory might reveal secrets hidden in plain sight.
2. Windows stores credentials in memory - tools like pypykatz can help extract them.
3. NTLM hashes can be cracked to reveal the original passwords.
4. Look for user credentials in the memory dump.

## Solution
1. Use pypykatz to extract NTLM hashes from the memory dump:
   ```bash
   pypykatz lsa minidump memory.dmp
   ```

2. Find the NTLM hash from the output:
   ```
   e4363571e5b2341e0da118fad002abb2
   ```

3. Use hashcat with the provided wordlist to crack the hash:
   ```bash
   hashcat -m 1000 e4363571e5b2341e0da118fad002abb2 wordlist.txt
   ```

4. The password is revealed:
   ```
   t00simpl3chall
   ```

## Flag
```
flag{t00simpl3chall}
``` 