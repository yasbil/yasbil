
# Changelog

### 2.0.3
- fix: SERP scrape JSON array needed to be stringified to sync

### 2.0.2
- fix: select_all for very large tables: try limit 2000 rows and go down
- WP: added project and user columns to `yasbil_largestring` table.

### 2.0.1
- fix: sync for very large tables: `MAX_PAYLOAD_SIZE` 4MB; sync inside a loop, in chunks of `max_payload_size`, as long as unsynced rows found


### 2.0.0
- refactored files, added modular code
- sync message changed to tabular structure
- added local viewing of data