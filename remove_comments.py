import re
import sys

def remove_html_comments(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    # Remove HTML comments
    content_no_comments = re.sub(r'<!--[\s\S]*?-->', '', content)
    
    with open(file_path, 'w', encoding='utf-8') as file:
        file.write(content_no_comments)
    
    print(f"Comments removed from {file_path}")

if __name__ == "__main__":
    if len(sys.argv) > 1:
        file_path = sys.argv[1]
        remove_html_comments(file_path)
    else:
        print("Please provide a file path")
