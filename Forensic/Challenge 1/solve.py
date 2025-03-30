#!/usr/bin/env python3
"""
Solve.py - Repair corrupted PNG headers

This script fixes the corrupted PNG header in the Haunted Header forensics challenge.
It repairs exactly the bytes that were corrupted in steg_creator.py.
"""

import os
import sys

def fix_png_header(corrupted_path, fixed_path):
    """
    Fix the corrupted PNG header
    
    Repairs:
    1. The first byte of the PNG signature (byte 0)
    2. The corrupted byte in the IHDR chunk (byte 12)
    """
    # Read the corrupted file
    with open(corrupted_path, 'rb') as f:
        data = bytearray(f.read())
    
    # Fix the first byte (PNG signature)
    # PNG files should start with: 89 50 4E 47 0D 0A 1A 0A
    data[0] = 0x89
    
    # Fix byte 12 in the IHDR chunk
    data[12] = 0x49  # This is the correct value for our image
    
    # Write the fixed file
    with open(fixed_path, 'wb') as f:
        f.write(data)
    
    print(f"Fixed PNG header in {fixed_path}")
    print("Repaired: First byte of PNG signature (0x00 -> 0x89)")
    print("Repaired: IHDR chunk byte 12 (0x00 -> 0x49)")
    
    return True

def main():
    if len(sys.argv) < 2:
        print("Usage: python solve.py <corrupted_image.png> [output_image.png]")
        print("If output image is not specified, will use 'fixed_<input_filename>'")
        return
    
    corrupted_path = sys.argv[1]
    
    if len(sys.argv) >= 3:
        fixed_path = sys.argv[2]
    else:
        # Generate output filename based on input
        filename = os.path.basename(corrupted_path)
        fixed_path = "fixed_" + filename
    
    # Check if input file exists
    if not os.path.exists(corrupted_path):
        print(f"Error: Input file '{corrupted_path}' not found.")
        return
    
    # Fix the PNG
    if fix_png_header(corrupted_path, fixed_path):
        print(f"\nSuccess! The fixed image has been saved to: {fixed_path}")
        print("You should now be able to open and view the image normally.")

if __name__ == "__main__":
    main() 