FROM antt1995/dsp-mediawiki:base

ENV MEDIAWIKI_MAJOR_VERSION 1.39
ENV MEDIAWIKI_VERSION 1.39.6

# MediaWiki setup
RUN fetchDeps=" \
	gnupg \
	dirmngr \
"; \
	apt-get update \
	&& apt-get install -y --no-install-recommends $fetchDeps \
	&& curl -fSL "https://releases.wikimedia.org/mediawiki/${MEDIAWIKI_MAJOR_VERSION}/mediawiki-${MEDIAWIKI_VERSION}.tar.gz" -o mediawiki.tar.gz \
	&& curl -fSL "https://releases.wikimedia.org/mediawiki/${MEDIAWIKI_MAJOR_VERSION}/mediawiki-${MEDIAWIKI_VERSION}.tar.gz.sig" -o mediawiki.tar.gz.sig \
	&& export GNUPGHOME="$(mktemp -d)" \
	&& gpg --batch --keyserver keyserver.ubuntu.com --recv-keys \
	D7D6767D135A514BEB86E9BA75682B08E8A3FEC4 \
	441276E9CCD15F44F6D97D18C119E1A64D70938E \
	F7F780D82EBFB8A56556E7EE82403E59F9F8CD79 \
	1D98867E82982C8FE0ABC25F9B69B3109D3BB7B0 \
	&& gpg --batch --verify mediawiki.tar.gz.sig mediawiki.tar.gz \
	&& tar -x --strip-components=1 -f mediawiki.tar.gz \
	&& gpgconf --kill all \
	&& rm -r "$GNUPGHOME" mediawiki.tar.gz.sig mediawiki.tar.gz \
	&& chown -R www-data:www-data extensions skins cache images \
	&& apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $fetchDeps \
	&& rm -rf /var/lib/apt/lists/*

COPY ./config/LocalSettings.php /var/www/html/LocalSettings.php

RUN cd /var/www/html/ && rm FAQ HISTORY SECURITY UPGRADE INSTALL CREDITS COPYING CODE_OF_CONDUCT.md README.md RELEASE-NOTES-1.39

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.local.json /var/www/html

RUN set -eux; \
	chown -R www-data:www-data /var/www

WORKDIR /var/www/html

USER www-data

RUN set -eux; \
	/usr/bin/composer config --no-plugins allow-plugins.composer/installers true; \
	/usr/bin/composer install --no-dev \
							--ignore-platform-reqs \
							--no-ansi \
							--no-interaction \
							--no-scripts; \
	rm -f composer.lock.json ;\
	/usr/bin/composer update --no-dev \
                            --no-ansi \
                            --no-interaction \
                            --no-scripts; \
	\
	mv /var/www/mediawiki/extensions/Checkuser /var/www/mediawiki/extensions/CheckUser; \
	mv /var/www/mediawiki/extensions/Dismissablesitenotice /var/www/mediawiki/extensions/DismissableSiteNotice; \
	mv /var/www/mediawiki/extensions/Nativesvghandler /var/www/mediawiki/extensions/NativeSvgHandler; \
	mv /var/www/mediawiki/extensions/Mediasearch /var/www/mediawiki/extensions/MediaSearch; \
	mv /var/www/mediawiki/extensions/Revisionslider /var/www/mediawiki/extensions/RevisionSlider; \
	mv /var/www/mediawiki/extensions/Rss /var/www/mediawiki/extensions/RSS; \
	mv /var/www/mediawiki/extensions/Shortdescription /var/www/mediawiki/extensions/ShortDescription; \
	mv /var/www/mediawiki/extensions/Webauthn /var/www/mediawiki/extensions/WebAuthn; \
	mv /var/www/mediawiki/extensions/Twocolconflict /var/www/mediawiki/extensions/TwoColConflict; \
	mv /var/www/mediawiki/extensions/Pageviewinfo /var/www/mediawiki/extensions/PageViewInfo; \
	mv /var/www/mediawiki/extensions/Mobilefrontend /var/www/mediawiki/extensions/MobileFrontend; \
	mv /var/www/html/skins/citizen /var/www/html/skins/Citizen; \
	\

	chown -R www-data:www-data /var/www/html


CMD ["apache2-foreground"]
