F — PHP Filesystem Library

Description:
A compact and powerful PHP library that provides unified, safe, and consistent
filesystem operations including scanning, searching, reading, writing, copying,
permissions, timestamps, and path utilities.

🎉 Initial Release — v1.0.0

This is the first official release of the F PHP filesystem utility library, a compact and feature-rich toolkit providing unified, safe, and predictable filesystem operations.

🚀 Key Features
📁 Filesystem Scanning
- Recursive and non-recursive directory scanning
- File, directory, and symlink filtering
- Depth limiting
- Absolute/relative path modes
- Cross-platform path normalization
  
🔍 Advanced File Search
- Rich find() method with filters for:
- type
- size (min/max)
- timestamps
- recursive mode
- Flexible output controlled by metadata flags
  
📦 Size & Disk Space Tools
- Full file and folder size calculation (recursive)
- Total, free, and used disk space reporting
  
🗂 File & Directory Management
- Create folders (make())
- Safe removal of files, directories, and symlinks
- Copying of files and directories
- Renaming and moving entities
  
📝 Reading & Writing
- Plain text read/write/append
- INI support (read_ini(), write_ini())
- JSON support (read_json(), write_json(), update_json())
- Automatic INI saving through internal buffer (__destruct())
  
🔧 Permissions
- Read/write permissions via permission()
- Octal string format for full compatibility (e.g., 0755)
  
⏱ Timestamps
- Get or update:
- access time
- modification time
- creation time

🛠 Path Utilities
- Safe path normalization
- Removal of duplicate slashes
- Cleaning of . and .. segments

🔒 Stability & Architecture
- Self-contained single-file library
- Predictable behavior across all operations
- Unified and consistent API design
- Internal configuration buffer system
- Fully compatible with PHP 8+

📦 Additional Notes
- Includes constants for all scan modes, keys, and filters
- Works on both Linux and Windows
- Clean modular internal structure
