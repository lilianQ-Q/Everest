FROM php:8.0-cli
COPY . /everest
WORKDIR /everest
CMD ["php", "bin/launcher.php"]