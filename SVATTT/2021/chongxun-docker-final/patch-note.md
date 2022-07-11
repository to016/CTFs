### Patching note

- Team can only control the patch in the file: `web02-daemon-final/patch/patch.php`
- Submit a patch as a ZIP file in the following structure:

  ```
  ---patch.zip
     |---patch
         |---patch.php
  ```

- A patch file is invalid if one of the following occurs (including but not limited to):
    - The intended functionality or behaviour of the challenge is changed. Every intended functionality or behaviour will be checked by the bot.
    - Size of the patch.php file: >= 2MB (Megabyte)

- Automatic checks can not be perfect. If you find out that a deployed patch is invalid, please report to us so that the patch can be manually taken down.

GLHF.

~Cheers,
ducnt