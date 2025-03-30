#!/usr/bin/env python3
import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont

# Flag to hide in the image
FLAG = "flag{f1x_th3_PNG_h34d3r_t0_r3v34l_m3}"

def create_flag_image(output_path):
    """Create a visible image with the flag text"""
    # Create a blank dark image
    width, height = 1500, 800
    img = np.zeros((height, width, 3), dtype=np.uint8)
    img[:] = (25, 10, 40)  # Dark purple background
    
    # Convert to PIL Image for text drawing
    pil_img = Image.fromarray(img)
    draw = ImageDraw.Draw(pil_img)
    
    # Use a default font if custom font is not available
    try:
        font_large = ImageFont.truetype("arial.ttf", 120)
        font_small = ImageFont.truetype("arial.ttf", 70)
    except IOError:
        font_large = ImageFont.load_default()
        font_small = font_large
    
    # Draw text
    draw.text((width//2 - 450, height//2 - 150), FLAG, 
              fill=(200, 100, 255), font=font_large, align="center")
    draw.text((width//2 - 400, height//2 + 120), "The ghosts are waiting...", 
              fill=(150, 150, 255), font=font_small, align="center")
    
    # Save the image
    cv2.imwrite(output_path, np.array(pil_img))
    print(f"Created flag image: {output_path}")

def corrupt_png(input_path, output_path):
    """Corrupt specific bytes in the PNG file"""
    with open(input_path, 'rb') as f:
        data = bytearray(f.read())
    
    # Corrupt first byte of PNG signature (should be 0x89)
    data[0] = 0x00
    
    # Corrupt a byte in the IHDR chunk (byte 12)
    data[12] = 0x00
    
    with open(output_path, 'wb') as f:
        f.write(data)
    
    print(f"Created corrupted PNG: {output_path}")
    print("Corrupted bytes: 0 (signature) and 12 (IHDR chunk)")

def main():
    # Create the visible flag image
    original_image = "flag_visible.png"
    create_flag_image(original_image)
    
    # Create a corrupted version
    corrupted_image = "haunted_image.png"
    corrupt_png(original_image, corrupted_image)
    
    print("\nChallenge files created successfully!")
    print(f"1. {corrupted_image} - Corrupted PNG with bytes 0 and 12 modified")
    print(f"2. {original_image} - Original visible flag for reference")
    print("Flag:", FLAG)

if __name__ == "__main__":
    main() 