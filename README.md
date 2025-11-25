### Laradock Configuration

1. Add Laradock as a submodule:
   ```bash
   git submodule add -f https://github.com/Laradock/laradock.git
   ```
2. Copy the contents of `.laradock` to the `laradock` directory, overwriting existing files.
3. Inside `laradock`, copy `.env.example` to `.env`.
4. In the `.env` file, set the following:
   ```
   PHP_VERSION=8.3
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
   WORKSPACE_NODE_VERSION=24
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
6. test

### Configure auto artisan commands after git pull

1. Copy `.git-sample/hooks/post-merge` file to `.git/hooks/post-merge` you can use `cp .git-sample/hooks/post-merge .git/hooks/post-merge` command.
2. Also run `chmod +x .git/hooks/post-merge` command.
