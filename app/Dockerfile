FROM php:8.1-apache
COPY src/ /var/www/html/
EXPOSE 80
# healthcheck (optional)
HEALTHCHECK --interval=10s --timeout=3s CMD curl -f http://localhost/ || exit 1
