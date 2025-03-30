# Haunted Header

## Challenge Description
A paranormal investigator found a strange image during their last ghost hunt, but it seems to be corrupted. They believe the image contains evidence of supernatural activity, but their computer can't open it properly. Can you repair the damaged file and reveal what ghostly secrets it might contain?

## Difficulty
Medium

## Category
Forensics

## Files Provided
- haunted_image.png (corrupted image)

## Hints
1. Look at the file format. PNG files have a specific magic number at the beginning.
2. The header of the image might be corrupted. What should the first few bytes of a PNG file be?
3. Sometimes you need to fix the "head" to see what's hiding inside.
4. Try using a hex editor to examine and repair the file structure.

## Solution
1. Examine the file with a hex editor to identify the corruption:
   ```bash
   xxd haunted_image.png | head
   ```

2. Observe that the PNG signature in the file header is incorrect. The correct PNG signature should be:
   ```
   89 50 4E 47 0D 0A 1A 0A
   ```

3. Replace the first 8 bytes with the correct PNG signature:
   ```bash
   # Using hexedit, dd, or any hex editor
   printf '\x89\x50\x4E\x47\x0D\x0A\x1A\x0A' | dd of=haunted_image.png bs=1 seek=0 count=8 conv=notrunc
   ```

4. Open the repaired image to reveal the hidden flag.

## Flag
```
flag{gh0stly_h34d3r_h4ck}
``` 