FROM nginx:1.21-alpine

WORKDIR /var/www/html

COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx"]

EXPOSE 80 443
