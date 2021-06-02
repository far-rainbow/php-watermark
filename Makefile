build:
	docker build --tag=${REGISTRY}/wm-php-74:${IMAGE_TAG} .
bench:
	docker run --rm --name bench-wm-php-74 wm-php-74
push:
	docker push ${REGISTRY}/wm-php-74:${IMAGE_TAG}
	
