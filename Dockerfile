FROM dspwiki/wiki-pre

LABEL maintainer="antt1995@antts.uk"

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
COPY ./resources/*.png ./resources/*.svg /var/www/html/skins/common/images/
COPY ./resources/SAIRAM.ttf /var/www/html/skins/common/font/

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
	mv /var/www/html/extensions/Checkuser /var/www/html/extensions/CheckUser; \
	mv /var/www/html/extensions/Dismissablesitenotice /var/www/html/extensions/DismissableSiteNotice; \
	mv /var/www/html/extensions/Nativesvghandler /var/www/html/extensions/NativeSvgHandler; \
	mv /var/www/html/extensions/Mediasearch /var/www/html/extensions/MediaSearch; \
	mv /var/www/html/extensions/Revisionslider /var/www/html/extensions/RevisionSlider; \
	mv /var/www/html/extensions/Rss /var/www/html/extensions/RSS; \
	mv /var/www/html/extensions/Shortdescription /var/www/html/extensions/ShortDescription; \
	mv /var/www/html/extensions/Webauthn /var/www/html/extensions/WebAuthn; \
	mv /var/www/html/extensions/Twocolconflict /var/www/html/extensions/TwoColConflict; \
	mv /var/www/html/extensions/Pageviewinfo /var/www/html/extensions/PageViewInfo; \
	mv /var/www/html/extensions/Mobilefrontend /var/www/html/extensions/MobileFrontend; \
	mv /var/www/html/extensions/Cleanchanges /var/www/html/extensions/CleanChanges; \
	mv /var/www/html/extensions/Antispam/Antispam /var/www/html/extensions/Temp; \
	rm /var/www/html/extensions/Antispam -r; \
	mv /var/www/html/extensions/Temp /var/www/html/extensions/Antispam; \
	mv /var/www/html/extensions/Css /var/www/html/extensions/CSS; \
	mv /var/www/html/extensions/DiscordNotifications /var/www/html/extensions/DiscordRCFeed; \
	mv /var/www/html/extensions/Usermerge /var/www/html/extensions/UserMerge; \
	mv /var/www/html/extensions/Cldr /var/www/html/extensions/cldr; \
	mv /var/www/html/skins/citizen /var/www/html/skins/Citizen; \
	\
	chown -R www-data:www-data /var/www/html

CMD ["apache2-foreground"]
