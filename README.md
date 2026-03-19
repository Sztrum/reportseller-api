### Laradock Configuration

1. Important: disable secure boot in BIOS
2. Add Laradock:
   ```bash
   git clone https://github.com/Laradock/laradock.git laradock
   ```
3. Copy the contents of `.laradock` to the `laradock` directory, overwriting existing files.
- Using `cp -rT .laradock laradock` command.
3. Inside `laradock`, copy `.env.example` to `.env`.
4. In the `.env` file, set the following:
   ```
   PHP_VERSION=8.5
   PHP_WORKER_INSTALL_REDIS=true
   PHP_WORKER_INSTALL_GD=true
   PHP_FPM_INSTALL_EXIF=true
   WORKSPACE_INSTALL_IMAGEMAGICK=true
   PHP_FPM_INSTALL_IMAGEMAGICK=true
   PHP_WORKER_INSTALL_IMAGEMAGICK=true
   PHP_FPM_INSTALL_GHOSTSCRIPT=true
   PHP_WORKER_INSTALL_GHOSTSCRIPT=true
   PHP_WORKER_INSTALL_ZIP_ARCHIVE=true
   WORKSPACE_INSTALL_SOAP=true
   PHP_FPM_INSTALL_SOAP=true
   PHP_WORKER_INSTALL_SOAP=true
   PHP_FPM_INSTALL_OPCACHE=false
   WORKSPACE_AST_VERSION=1.1.3
   WORKSPACE_NODE_VERSION=22
   ```

   Additionally, update all container user IDs to avoid clashes with the base image:
   ```
   WORKSPACE_PUID=1001
   WORKSPACE_PGID=1001 
   PHP_FPM_PUID=1002
   PHP_FPM_PGID=1002 
   PHP_WORKER_PUID=1003
   PHP_WORKER_PGID=1003 
   LARAVEL_HORIZON_PUID=1004
   LARAVEL_HORIZON_PGID=1004
   ```

5. For Windows, set `DOCKER_SYNC_STRATEGY=unison` in `.env`. On macOS, leave `native_osx`.
6. Set the project-specific settings:
   ```
   COMPOSE_PROJECT_NAME=reportseller-api
   DATA_PATH_HOST=~/.laradock/reportseller-api
   ```
7. Run: 
   ```bash
   sh start.sh
   ```
   to start the Docker containers.

## Project configuration

1. Run `composer install` command.
2. Copy `.env.example` to `.env` file and fill required data.
3. Create 2 databases, first for main application, second for telescope.
4. Run `php artisan migrate` command.
5. Run `npm install` command.

### Configure auto artisan commands after git pull

1. Copy `.git-sample/hooks/post-merge` file to `.git/hooks/post-merge` you can use `cp .git-sample/hooks/post-merge .git/hooks/post-merge` command.
2. Also run `chmod +x .git/hooks/post-merge` command.

## Codex CLI setup

To enable commonly used Codex terminal actions without repeated approvals, update your local Codex config.

1. Edit `~/.codex/config.toml` and add:
   ```toml
   approval_policy = "on-request"
   sandbox_mode = "workspace-write"

   [sandbox_workspace_write]
   network_access = true
   writable_roots = [
     "<PROJECT_PATH>",
     "/tmp",
     "<YOUR_HOME>/.gnupg",
     "<YOUR_HOME>/.gitconfig"
   ]
   ```
   Notes:
    - Use absolute paths in `writable_roots`.
    - Replace `<YOUR_HOME>` and `<PROJECT_PATH>` with your local values, for example:
        - `<YOUR_HOME>` -> `/home/alice`
        - `<PROJECT_PATH>` -> `/home/alice/projects/php8.3/configurator`
    - Each developer should set their own home/project paths; do not hardcode one user path for everyone.
2. Edit `~/.codex/rules/default.rules` and add:
   ```python
   prefix_rule(pattern = ["gh", "pr"], decision = "allow")
   prefix_rule(pattern = ["gh", "api"], decision = "allow")
   prefix_rule(pattern = ["git", "push"], decision = "allow")
   prefix_rule(pattern = ["git", "pull"], decision = "allow")
   prefix_rule(pattern = ["git", "checkout"], decision = "allow")
   prefix_rule(pattern = ["git", "switch"], decision = "allow")
   prefix_rule(pattern = ["cat"], decision = "allow")
   prefix_rule(pattern = ["bash"], decision = "allow")
   prefix_rule(pattern = ["/bin/bash"], decision = "allow")
   ```
3. Restart Codex CLI.
4. Optionally verify rule matching:
   ```bash
   codex execpolicy check --pretty --rules ~/.codex/rules/default.rules -- gh pr view 1
   codex execpolicy check --pretty --rules ~/.codex/rules/default.rules -- git push
   ```

Note: `cat > file` uses shell redirection and may still be evaluated as `bash -lc ...`, not plain `cat`.

### Configure to not restart after docker restart
`sed -i 's/restart: always/restart: "no"/g' laradock/docker-compose.yml && cd laradock && sh start.sh && sh stop.sh && cd ../`

## TODO

- Prepare scraping of external listing URLs through a proxy layer to improve reliability and reduce blocking risk for providers such as OLX.
- Review all supported listing scrapers and align extracted category, seller type, and availability fields across every platform-specific implementation.
