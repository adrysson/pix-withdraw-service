install-dependencies:
	docker run -v ${PWD}:/opt/www -p 9501:9501 -w /opt/www --rm hyperf/hyperf:8.3-alpine-v3.22-swoole composer install