F — PHP Filesystem Library<br>
Version: 1.0.1
PHP Version: 8.0+

**Description:**<br>
A compact and powerful PHP library that provides unified, safe, and consistent
filesystem operations including scanning, searching, reading, writing, copying,
permissions, timestamps, and path utilities.
<hr>

**Accessed**<br>
$f->accessed — Get or update file last access time

**Description**<br>
`$f->accessed(string $path, int|null $time = null): int|null`

Gets the last access timestamp of a file, or updates it if $time is provided.

**Parameters**<br>
- path – Path to the file or directory.
- time – New access time as a Unix timestamp. If null, no update is performed.

**Return Values**<br>
- Unix timestamp on success
- null on failure

<hr>

**Append**<br>
$f->append — Append data to a file

**Description**<br>
`$f->append(string $path, string $data, int|null $maxlen = null): bool`

Appends data to the end of a file. If $maxlen is specified, only up to $maxlen bytes will be written starting from the current end of the file.

**Parameters**<br>
- path – Path to the file.
- data – Data to append to the file.
- maxlen – Maximum number of bytes to write. If null, all data is appended.

**Return Values**<br>
- true on success
- false on failure

<hr>

**Copy**<br>
$f->copy — Copy a file, directory, or symbolic link

**Description**<br>
`$f->copy(string $source, string $dest, string|null $name = null): bool`

Copies a file, directory, or symbolic link from a source path to a destination path.
- If $name is provided, the copied item will use that name; otherwise, the original basename is used.
- Directories are copied recursively, including all their contents.
- Symbolic links are preserved as links.

**Parameters**<br>
- source – Path to the file, directory, or symbolic link to copy.
- dest – Destination directory path.
- name – Optional new name for the copied item. If null, the original name is kept.

**Return Values**<br>
- true on success
- false on failure

<hr>

**Created**<br>
$f->created — Get file creation time

**Description**<br>
`$f->created(string $path): int|false`

Gets the creation timestamp of a file.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- Unix timestamp of file creation on success
- null on failure

<hr>

**Cut**<br>
$f->cut — Move a file, directory, or symbolic link

**Description**<br>
`$f->cut(string $source, string $dest, string|null $name = null): bool`

Moves a file, directory, or symbolic link from a source path to a destination path.
- Internally, it copies the item to the destination and then removes the original.
- If $name is provided, the moved item will use that name; otherwise, the original basename is used.

**Parameters**<br>
- source – Path to the file, directory, or symbolic link to move.
- dest – Destination directory path.
- name – Optional new name for the moved item. If null, the original name is kept.

**Return Values**<br>
- true on success
- false on failure

<hr>

**Extension**<br>
$f->extension — Get file extension

**Description**<br>
`$f->extension(string $path): string`

Returns the file extension of the given path.

**Parameters**<br>
- path – Path to the file.

**Return Values**<br>
- File extension as a string (without the dot).

<hr>

**Filename**<br>
$f->filename — Get filename without extension

**Description**<br>
`$f->filename(string $path): string`

Returns the file name without its extension from the given path.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- File name as a string (without the extension).

<hr>

**Find**<br>
$f->find — Find files matching a pattern

**Description**<br>
`$f->find(string $path, int $key = F_SCAN_ALL, int $keys = F_ALL, bool $recursive = false, string|null $query = null, int|null $depth = null): array`

Scans a directory for files, directories, or links and returns detailed information about matching items.
- Can filter by name pattern, size range, and access/creation/modification times.
- Supports recursive scanning and limiting the depth of recursion.
- The $keys parameter determines which attributes are included in the result.

**Parameters**<br>
- path – Path to the file or directory.
- key – Type of items to scan (e.g., files, directories, links). Default is F_SCAN_ALL.
- keys – Which attributes to include in the result (default F_ALL).
- recursive – Whether to scan subdirectories recursively.
- query – JSON string with optional filters:
	- pattern – File name pattern (supports wildcards).
	- size – Object with min and max size in bytes.
	- time – Object with optional atime, ctime, mtime limits (each with min and max Unix timestamps).
- depth – Maximum recursion depth (null for unlimited).

**Return Values**<br>
An associative array where keys are full paths and values are arrays of file attributes. Possible attributes include:
- atime, ctime, mtime – Access, creation, and modification timestamps.
- size – File size in bytes.
- name – File name with extension.
- filename – File name without extension.
- extension – File extension.
- type – Item type (file, dir, link, or unknown).
- permission – File permissions.

<hr>

**Ini Get**<br>
$f->ini_get — Get or set a value from an INI file

**Description**<br>
`$f->ini_get(string $path, string $section, string $key, mixed $value = ""): mixed`

Reads a value from an INI file, optionally setting a default if the key does not exist.
- The INI file is read once and cached internally for subsequent calls.
- If the specified key does not exist, the provided $value is returned and stored in the cache.

**Parameters**<br>
- path – Path to the file.
- section – INI section name.
- key – Key within the section.
- value – Default value to use if the key does not exist.

**Return Values**<br>
- Value from the INI file if it exists, otherwise the provided default value.

<hr>

**Ini Set**<br>
$f->ini_set — Set a value in an INI file

**Description**<br>
`$f->ini_set(string $path, string $section, string $key, mixed $value): mixed`

Sets a value in an INI file and updates the internal cache.
- If the INI file has not been read yet, it will be loaded first.
- The new value is stored in the cache immediately.

**Parameters**<br>
- path – Path to the file.
- section – INI section name.
- key – Key within the section to set.
- value – Value to assign to the key.

**Return Values**<br>
- The value that was set.

<hr>

**Location**<br>
$f->location — Get directory path of a file

**Description**<br>
`$f->location(string $path): string`

Returns the directory path (parent folder) of the given file path.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- Directory path as a string.

<hr>

**Modified**<br>
$f->modified — Get or update file modification time

**Description**<br>
`$f->modified(string $path, int|null $time = null): int|null`

Gets the last modification timestamp of a file, or updates it if $time is provided.

**Parameters**<br>
- path – Path to the file or directory.
- time – New modification time as a Unix timestamp. If null, no update is performed.

**Return Values**<br>
- Unix timestamp of last modification on success
- null on failure

<hr>

**Make**<br>
$f->make — Create a directory

**Description**<br>
`$f->make(string $path, string $chmod = '0755'): bool`

Creates a directory at the specified path.
- If the directory already exists, the function returns true.
- The $chmod parameter sets the permissions for the new directory.

**Parameters**<br>
- path – Path to the directory.
- chmod – Directory permissions in octal string format (default '0755').

**Return Values**<br>
- true on success or if the directory already exists
- false on failure

<hr>

**Name**<br>
$f->name — Get file name with extension

**Description**<br>
`$f->name(string $path): string`

Returns the file name including its extension from the given path.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- File name as a string (including extension, if any).

<hr>

**Normalize**<br>
$f->normalize — Normalize a file or directory path

**Description**<br>
`$f->normalize(string $path): string|false`

Converts a file or directory path to a normalized absolute form.
- Resolves relative segments like . and ...
- Converts backslashes to forward slashes.
- Preserves UNC paths (//server/share) and drive letters (Windows).
- Throws an exception if the path contains invalid characters or cannot be normalized.
- Uses internal caching to avoid repeated normalization of the same path.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- Normalized absolute path as a string on success
- false if normalization fails

<hr>

**Permission**<br>
$f->permission — Get or set file permissions

**Description**<br>
`$f->permission(string $path, string|null $chmod = null): string|bool`

Gets the permissions of a file or sets new permissions if $chmod is provided.
- When $chmod is null, returns the current permissions in octal string format.
- When $chmod is provided, sets the permissions and returns the result of chmod().

**Parameters**<br>
- path – Path to the file or directory.
- chmod – Optional new permissions in octal string format (e.g., '0755'). If null, no change is made.

**Return Values**<br>
- Current permissions as a string (octal) when $chmod is null
- true on successful permission change
- false on failure

<hr>

**Remove**<br>
$f->remove — Delete a file, directory, or symbolic link

**Description**<br>
`$f->remove(string $path): bool`

Removes a file, directory, or symbolic link.
- Files and links are deleted directly.
- Directories are deleted recursively, including all their contents.
- Clears the file status cache after deletion.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- true on successful deletion
- false on failure

<hr>

**Read**<br>
$f->read — Read data from a file

**Description**<br>
`$f->read(string $path, int|null $offset = null, int|null $maxlen = null): string`

Reads data from a file with optional offset and maximum length.
- Supports reading from a specific offset (negative offsets count from the end).
- Supports reading up to a maximum number of bytes.
- Uses shared non-blocking file locking to ensure safe reading.

**Parameters**<br>
- path – Path to the file.
- offset – Optional starting position in bytes. Default is 0. Negative values count from the end of the file.
- maxlen – Optional maximum number of bytes to read. Default is the remainder of the file.

**Return Values**<br>
- String containing the read data
- Empty string if the file does not exist, is empty, or reading fails

<hr>

**Read CSV**<br>
$f->read_csv — Read CSV file into an array

**Description**<br>
`$f->read_csv(string $path, string $separator = ';'): array`

Reads a CSV file and returns its contents as a two-dimensional array.
- Supports custom field separators (default is ;).
- Strips UTF-8 BOM if present.
- Ignores empty lines.
- Removes empty trailing values from each row.

**Parameters**<br>
- path – Path to the file.
- separator – Field separator character. Default is ';'.

**Return Values**<br>
- Two-dimensional array of CSV rows and fields
- Empty array if the file is empty or cannot be read

<hr>

**Read INI**<br>
$f->read_ini — Read an INI file into an array

**Description**<br>
`$f->read_ini(string $path): array`

Reads an INI file and parses its contents into an associative array.
- Uses parse_ini_string with INI_SCANNER_TYPED to automatically convert values to appropriate types.
- Returns an empty array if the file cannot be read or parsed.

**Parameters**<br>
- path – Path to the file.

**Return Values**<br>
- Associative array representing the INI file contents
- Empty array on failure

<hr>

**Read JSON**<br>
$f->read_json — Read a JSON file

**Description**<br>
`$f->read_json(string $path): mixed`

Reads a JSON file and decodes its contents into a PHP value.
- Returns an associative array if the JSON represents an object.
- Returns null if the file cannot be read or the JSON is invalid.

**Parameters**<br>
- path – Path to the file.

**Return Values**<br>
- Decoded JSON value (array, object, string, number, etc.)
- null on failure

<hr>

**Rename**<br>
$f->rename — Rename a file or directory

**Description**<br>
`$f->rename(string $path, string|null $name = null): bool`

Renames a file or directory.
- If $name is provided, the item is renamed to that name; otherwise, its basename is used (effectively no change).
- The operation fails if a file or directory with the target name already exists.

**Parameters**<br>
- path – Path to the file or directory.
- name – Optional new name. If null, the original basename is used.

**Return Values**<br>
- true on success
- false on failure

<hr>

**Scan**<br>
$f->scan — Scan a directory for files, directories, or links

**Description**<br>
`$f->scan(string $path, int $key = F_SCAN_ALL, bool $absolute = false, bool $recursive = false, int|null $depth = null): array`

Scans a directory and returns a list of files, directories, or symbolic links.
- Can filter by type: files, directories, links, or all.
- Supports recursive scanning with optional depth limit.
- Can return either absolute paths or just basenames.

**Parameters**<br>
- path – Path to the directory.
- key – Type of items to scan (F_SCAN_ALL, F_SCAN_FILES, F_SCAN_DIRS, F_SCAN_LINKS). Default is F_SCAN_ALL.
- absolute – If true, returns absolute paths; otherwise, returns basenames.
- recursive – Whether to scan subdirectories recursively.
- depth – Optional maximum recursion depth. Null for unlimited depth.

**Return Values**<br>
- Array of matching file, directory, or link paths (absolute or basename depending on $absolute)

<hr>

**Size**<br>
$f->size — Get size of a file or directory

**Description**<br>
`$f->size(string $path): int`

Returns the size in bytes of a file, symbolic link, or directory.
- For files, returns the file size.
- For symbolic links, returns 0.
- For directories, returns the total size of all contained files and subdirectories recursively.

**Parameters**<br>
- path – Path to the file or directory.

**Return Values**<br>
- Size in bytes as an integer

<hr>

**Space**<br>
$f->space — Get disk space information

**Description**<br>
`$f->space(string $path, int $key = F_SPACE_TOTAL): int`

Returns disk space information for the filesystem containing the specified path.
- Can return total space, used space, or free space based on $key.

**Parameters**<br>
- path – Path to the directory.
- key – Type of space to return:
	- F_SPACE_TOTAL – Total disk space (default)
	- F_SPACE_USED – Used disk space
	- F_SPACE_FREE – Free disk space

**Return Values**<br>
- Space in bytes as an integer

<hr>

**Write**<br>
$f->write — Write data to a file

**Description**<br>
`$f->write(string $path, string $data = '', int|null $offset = null, int|null $maxlen = null): bool`

Writes data to a file with optional offset and maximum length.
- Creates the parent directory if it does not exist.
- Supports writing at a specific offset (negative offsets count from the end).
- Supports limiting the number of bytes written using $maxlen.
- Uses exclusive non-blocking file locking to ensure safe writing.

**Parameters**<br>
- path – Path to the file.
- data – Data to write to the file.
- offset – Optional starting position in bytes. Default is 0 (overwrites file). Negative values count from the end.
- maxlen – Optional maximum number of bytes to write.

**Return Values**<br>
- true on successful write
- false on failure

<hr>

**Write CSV**<br>
$f->write_csv — Write an array to a CSV file

**Description**<br>
`$f->write_csv(string $path, array $array = [], string $separator = ';'): bool`

Writes a two-dimensional array to a CSV file.
- Converts all values to strings.
- Uses the specified separator between fields (default is ;).
- Appends the separator at the end of each row.

**Parameters**<br>
- path – Path to the file.
- array – Two-dimensional array to write.
- separator – Field separator character (default ';').

**Return Values**<br>
- true on successful write
- false on failure

<hr>

**Write INI**<br>
$f->write_ini — Write an array to an INI file

**Description**<br>
`$f->write_ini(string $path, array $array = []): bool`

Writes an associative array to an INI file.
- Each top-level key represents a section.
- Supports string, integer, float, boolean, and null values.
- Strings are escaped and quoted properly.
- Skips invalid section names or keys.

**Parameters**<br>
- path – Path to the file.
- array – Associative array to write. The top-level keys are sections, and their values are key-value pairs.

**Return Values**<br>
- true on successful write
- false on failure

<hr>

**Write JSON**<br>
$f->write_json — Write an array to a JSON file

**Description**<br>
`$f->write_json(string $path, array $array = []): bool`

Encodes an array as JSON and writes it to a file.
- Uses JSON_UNESCAPED_UNICODE and JSON_PRETTY_PRINT for readability.
- Returns false if encoding fails.

**Parameters**<br>
- path – Path to the file.
- array – Array to encode and write.

**Return Values**<br>
- true on successful write
- false on failure
