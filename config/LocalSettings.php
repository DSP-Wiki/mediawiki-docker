<?php

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

$wgSitename = "DSP Wiki";
$wgMetaNamespace = "DSP_Wiki";

$wgScriptPath = "";
$wgScriptExtension = ".php";
#$wgArticlePath = "/$1";
$wgUsePathInfo = true;

$actions = array( 'edit', 'watch', 'unwatch', 'delete','revert', 'rollback',
  'protect', 'unprotect', 'markpatrolled', 'render', 'submit', 'history', 'purge', 'info' );
foreach ( $actions as $action ) {
  $wgActionPaths[$action] = "/$1/$action";
}
$wgActionPaths['view'] = "/$1";
$wgArticlePath = $wgActionPaths['view'];

$wgServer = "{$_ENV['WIKI_SERVER_URL']}";
$wgCanonicalServer  = "{$_ENV['WIKI_SERVER_URL']}";

$wgForceHTTPS = true;
$wgMainPageIsDomainRoot = true;
$wgSecureLogin = true;

$wgMaxShellMemory = 2097152; // 2GB
$wgMaxShellFileSize = 1048576; // 1GB
$wgMaxShellTime = 180; // 3 Minutes

$wgFragmentMode = [ 'html5' ];
$wgEnableCanonicalServerLink = true;
$wgResourceBasePath = $wgScriptPath;
$wgPasswordDefault = 'argon2';

$wgFavicon = "$wgResourceBasePath/images/favicon.ico";

$wgLogos = [
	'icon' => "$wgResourceBasePath/images/DSP_Logo.png",
    'wordmark' => [
		'src' => "$wgResourceBasePath/images/DSP_Logo.png",
    ],
    'tagline' => [
		'src' => "$wgResourceBasePath/images/DSP_Logo.png",		// path to tagline version
		'width' => 135,
		'height' => 15,
	],
];

$wgArticleCountMethod = 'any';

$wgNamespacesWithSubpages[NS_MAIN] = true;
$wgNamespacesWithSubpages[NS_TEMPLATE] = true;
$wgNamespacesWithSubpages[NS_USER] = true;


##################
#//*    Email 
##################

$wgEnableEmail = true;
$wgEnableUserEmail = true;

$wgEmergencyContact = "admin@dsp-wiki.com";
$wgPasswordSender = "no-reply@dsp-wiki.com";

$wgAllowHTMLEmail = true;
$wgEnotifUserTalk = false;
$wgEnotifWatchlist = false;
$wgEmailAuthentication = true;
$wgEmailConfirmToEdit = true;

##################
#//*    AWS 
##################

$wgSMTP = [
  'host'      => 'tls://email-smtp.eu-west-2.amazonaws.com',
  'IDHost'    => 'email-smtp.eu-west-2.amazonaws.com',
  'port'      => 465,
  'auth'      => true,
  'username'  => $_ENV['WIKI_AWS_KEY'],
  'password'  => $_ENV['WIKI_AWS_SECRET']
];

if ($_ENV['WIKI_S3_MODE'] == 'TRUE') {
  $wgAWSCredentials = [
      'key'    => $_ENV['WIKI_S3_KEY'],
      'secret' => $_ENV['WIKI_S3_SECRET'],
      'token'  => false
  ];
  $wgAWSBucketName = "{$_ENV['WIKI_S3_BUCKET']}";
  $wgAWSBucketDomain = "{$_ENV['WIKI_S3_DOMAIN']}";
  $wgAWSRepoHashLevels = '2';
  $wgAWSRepoDeletedHashLevels = '3';
  $wgFileBackends['s3']['endpoint'] = "{$_ENV['WIKI_S3_ENDPOINT']}";
  $wgAWSRegion = "{$_ENV['WIKI_S3_REGION']}";
};

##################
#//*    Database 
##################

$wgDBtype = "mysql";
$wgDBserver = "antts.uk";
$wgDBname = "{$_ENV['WIKI_DB_NAME']}";
$wgDBuser = "dspwiki";
$wgDBpassword = "{$_ENV['WIKI_DB_PASS']}";
$wgDBprefix = "";
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

##################
#//*    Cache 
##################

$wgMainCacheType = 'redis';
$wgSessionCacheType = 'redis';
$wgMemCachedServers = array();

##################
#//*    Footer 
##################

$wgFooterIcons = [
  "poweredby" => [
    "mediawiki" => [
      "src" => "$wgScriptPath/skins/common/images/badge-mediawiki.svg",
      "url" => "https://www.mediawiki.org",
      "alt" => "Powered by MediaWiki",
      "height" => "42",
      "width" => "127",
    ],
  ],
  "copyright" => [
    "copyright" => [
      "src" => "$wgScriptPath/skins/common/images/badge-ccbysa.svg",
      "url" => "https://creativecommons.org/licenses/by-sa/4.0/",
      "alt" => "Creative Commons Attribution-ShareAlike",
      "height" => "50",
      "width" => "110",
    ]
  ]
];

$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "https://creativecommons.org/licenses/by-sa/4.0/";
$wgRightsText = "Creative Commons Attribution-ShareAlike";
$wgRightsIcon = "$wgResourceBasePath/resources/assets/licenses/cc-by-sa.png";

# Add links to footer
$wgHooks['SkinAddFooterLinks'][] = function ( $sk, $key, &$footerlinks ) {
	$rel = 'nofollow noreferrer noopener';

	if ( $key === 'places' ) {
		$footerlinks['analytics'] = Html::element(
			'a',
			[
				'href' => "https://analytics.dsp-wiki.com/{$_ENV['WIKI_PLAUSIBLE_DOMAIN']}",
				'rel' => $rel
			],
			$sk->msg( 'footer-analytics' )->text()
		);
	}
};

$wgULSLanguageDetection = false;
$wgULSIMEEnabled = false;

##################
#//*     Images
##################
$wgEnableUploads = true;
$wgGenerateThumbnailOnParse = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";
$wgFileExtensions = array_merge( $wgFileExtensions,
    array( 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'svg', 'xml' )
    ); 
$wgAllowTitlesInSVG = true;
$wgSVGConverter = 'ImageMagick';
$wgUseInstantCommons = true;
$wgPingback = false;
$wgShellLocale = "C.UTF-8";
$wgGenerateThumbnailOnParse = true;
$wgThumbnailEpoch = "20190815000000";
$wgIgnoreImageErrors = true;
$wgMaxImageArea = 6.4e7;
$wgNativeImageLazyLoading = true;

$wgLanguageCode = "en";
$wgLocaltimezone = "GMT";
$wgCacheDirectory = "$IP/cache";
$wgSecretKey = "{$_ENV['WIKI_SECRET_KEY']}";
$wgAuthenticationTokenVersion = "1";
#$wgUpgradeKey = "{$_ENV['WIKI_UPGRADE_KEY']}";
$wgDiff3 = "/usr/bin/diff3";

##################
#//*   Theme
##################

$wgDefaultSkin = "Citizen";
wfLoadSkin( 'Citizen' );
$wgCitizenThemeDefault = 'dark';
$wgDefaultMobileSkin = 'citizen';
$wgCitizenEnableCJKFonts = true;
$wgCitizenEnablePreferences = false;
$wgCitizenShowPageTools = 'permission';
$wgCitizenThemeColor = '#1D1D1D';
$wgAllowSiteCSSOnRestrictedPages = true;

##################
#//*   Enabled extensions
##################
if ($_ENV['WIKI_S3_MODE'] == 'TRUE') { wfLoadExtension( 'AWS' ); };
wfLoadExtension( 'AdvancedSearch' );
wfLoadExtension( 'Babel' );
wfLoadExtension( 'Capiunto' );
wfLoadExtension( 'Cargo' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'CheckUser' );
wfLoadExtension( 'Cite' );
wfLoadExtension( 'CiteThisPage' );
wfLoadExtension( 'cldr' );
wfLoadExtension( 'CleanChanges' );
wfLoadExtension( 'CodeEditor' );
wfLoadExtension( 'CodeMirror' );
wfLoadExtension( 'CommonsMetadata' );
wfLoadExtensions([ 'ConfirmEdit', 'ConfirmEdit/ReCaptchaNoCaptcha' ]);
wfLoadExtension( 'CookieWarning' );
wfLoadExtension( 'CSS' );
wfLoadExtension( 'Disambiguator' );
wfLoadExtension( 'DiscussionTools' );
wfLoadExtension( 'DismissableSiteNotice' );
wfLoadExtension( 'DynamicPageList3' );
wfLoadExtension( 'Echo' );
wfLoadExtension( 'EmbedVideo' );
wfLoadExtension( 'Gadgets' );
wfLoadExtension( 'Graph' );
wfLoadExtension( 'ImageMap' );
wfLoadExtension( 'InputBox' );
wfLoadExtension( 'Interwiki' );
wfLoadExtension( 'JsonConfig' );
wfLoadExtension( 'Linter' );
wfLoadExtension( 'Loops' );
wfLoadExtension( 'Math' );
wfLoadExtension( 'MediaSearch' );
wfLoadExtension( 'MobileFrontend' );
wfLoadExtension( 'MultimediaViewer' );
wfLoadExtension( 'MultiPurge' );
wfLoadExtension( 'NativeSvgHandler' );
wfLoadExtension( 'Nuke' );
wfLoadExtension( 'OATHAuth' );
wfLoadExtension( 'PageImages' );
wfLoadExtension( 'PageViewInfo' );
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'PdfHandler' );
wfLoadExtension( 'PictureHtmlSupport' );
wfLoadExtension( 'Plausible' );
wfLoadExtension( 'Poem' );
wfLoadExtension( 'Popups' );
wfLoadExtension( 'RelatedArticles' );
wfLoadExtension( 'Renameuser' );
wfLoadExtension( 'ReplaceText' );
wfLoadExtension( 'RevisionSlider' );
wfLoadExtension( 'RSS' );
wfLoadExtension( 'SandboxLink' );
wfLoadExtension( 'Scribunto' );
wfLoadExtension( 'SecureLinkFixer' );
wfLoadExtension( 'ShortDescription' );
wfLoadExtension( 'SpamBlacklist' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'TabberNeue' );
wfLoadExtension( 'TemplateData' );
wfLoadExtension( 'TemplateStyles' );
wfLoadExtension( 'TemplateStylesExtender' );
wfLoadExtension( 'TextExtracts' );
wfLoadExtension( 'Thanks' );
wfLoadExtension( 'Translate' );
wfLoadExtension( 'TwoColConflict' );
wfLoadExtension( 'UniversalLanguageSelector' );
wfLoadExtension( 'UploadWizard' );
wfLoadExtension( 'UserMerge' );
wfLoadExtension( 'Variables' );
wfLoadExtension( 'VisualEditor' );
wfLoadExtension( 'WebAuthn' );
wfLoadExtension( 'WebP' );
wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'WikiSEO' );
wfLoadExtension( 'DiscordRCFeed' );
wfLoadExtension(  'AbuseFilter' );
wfLoadExtension(  'AdvancedSearch' );
wfLoadExtension(  'Antispam' );
wfLoadExtension(  'Capiunto' );

##################
#//*  Remove autoconfirmed
##################
unset( $wgGroupPermissions['autoconfirmed'] );
unset( $wgRevokePermissions['autoconfirmed'] );
unset( $wgAddGroups['autoconfirmed'] );
unset( $wgRemoveGroups['autoconfirmed'] );
unset( $wgGroupsAddToSelf['autoconfirmed'] );
unset( $wgGroupsRemoveFromSelf['autoconfirmed'] );
$wgImplicitGroups[] = 'autoconfirmed';

##################
#//*  Scribunto
##################
$wgScribuntoDefaultEngine = 'luastandalone';
$wgScribuntoUseGeSHi = true;
$wgScribuntoUseCodeEditor = true;
$wgTemplateDataUseGUI = true;

##################
#//*  Editors
##################
$wgDefaultUserOptions['visualeditor-enable'] = 1;
$wgDefaultUserOptions['visualeditor-editor'] = "visualeditor";
$wgDefaultUserOptions['visualeditor-newwikitext'] = 1;
$wgPrefs[] = 'visualeditor-enable';
$wgVisualEditorEnableWikitext = true;
$wgVisualEditorEnableDiffPage = true;
$wgVisualEditorUseSingleEditTab = true;
$wgVisualEditorEnableVisualSectionEditing = true;
$wgWikiEditorRealtimePreview = true;

$wgCommonsMetadataForceRecalculate = true;

##################
#//*   Plausible
##################
$wgPlausibleDomain = 'https://analytics.dsp-wiki.com';
$wgPlausibleDomainKey = "{$_ENV['WIKI_PLAUSIBLE_DOMAIN']}";
$wgPlausibleHonorDNT = true;
$wgPlausibleTrackLoggedIn = true;
$wgPlausibleTrackOutboundLinks = true;
$wgPlausibleIgnoredTitles = [ '/Special:*' ];
$wgPlausibleEnableCustomEvents = true;
$wgPlausibleTrack404 = true;
$wgPlausibleTrackSearchInput = true;
$wgPlausibleTrackEditButtonClicks = true;
$wgPlausibleTrackCitizenSearchLinks = true;
$wgPlausibleTrackCitizenMenuLinks = true;
$wgPlausibleApiKey = "{$_ENV['WIKI_PLAUSIBLE_API']}";

##################
#//*   Discord
##################

$wgRCFeeds['discord'] = [
	'url' => $_ENV['WIKI_DISCORD_URL'],
    'omit_talk' => true,
];
$wgRCFeeds['discord']['request_replace'] = [
	'username' => $_ENV['WIKI_DISCORD_NAME'],
    'avatar_url' => $_ENV['WIKI_DISCORD_LOGO'],
];
$wgRCFeeds['discord']['omit_log_types'] = [
	'newusers',
];

##################
#//*   Translation
##################

$wgCCTrailerFilter = true;
$wgCCUserFilter = false;
$wgDefaultUserOptions['usenewrc'] = 1;
$wgTranslateDocumentationLanguageCode = 'qqq';
$wgTranslateNewsletterPreference = false;
$wgTranslateFuzzyBotName = 'FuzzyBot';
$wgExtraLanguageNames['qqq'] = 'Message documentation'; # No linguistic content. Used for documenting messages
$wgEnablePageTranslation = true;
$wgPageTranslationNamespace = 1198;
$wgTranslatePageTranslationULS = false;

##################
#//*   TemplateStyles
##################
$wgTemplateStylesAllowedUrls = [
    "audio" => [""],
    "image" => ["<^/skins/common/images/>"],
    "svg" => [""],
    "font" => ["<^/skins/common/font/>"],
    "namespace" => ["<.>"],
    "css" => []
];
$wgInvalidateCacheOnLocalSettingsChange = true;
$wgTemplateStylesExtenderEnableUnscopingSupport = true;

##################
#//*      Name Spaces
##################

define("NS_MODDING", 3000);
define("NS_MODDING_TALK", 3001);
$wgExtraNamespaces[NS_MODDING] = "Modding";
$wgExtraNamespaces[NS_MODDING_TALK] = "Modding_Talk";
$wgContentNamespaces[] = NS_MODDING;
$wgNamespacesToBeSearchedDefault[NS_MODDING] = true;

define("NS_PATCH_NOTES", 3005);
define("NS_PATCH_TALK", 3006);
$wgExtraNamespaces[NS_PATCH_NOTES] = "Patch_Notes";
$wgExtraNamespaces[NS_PATCH_TALK] = "Patch_Notes_Talk";
$wgContentNamespaces[] = NS_PATCH_NOTES;
$wgNamespacesToBeSearchedDefault[NS_PATCH_NOTES] = true;
$wgNamespacesToBeSearchedDefault[NS_PATCH_TALK] = false;
##################
#//*      Permissions
##################
$wgUserMergeProtectedGroups = [];
$wgNamespaceProtection[NS_TEMPLATE] = ['templates'];
$wgNamespaceProtection[NS_PATCH_NOTES] = ['patchnotes'];

# all
$wgGroupPermissions['*']['createaccount'] = true;
$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['*']['createtalk'] = false;
$wgGroupPermissions['*']['createpage'] =  false;
$wgGroupPermissions['*']['writeapi'] = true;
$wgGroupPermissions['*']['editmyprivateinfo'] =  false;
$wgGroupPermissions['*']['editmywatchlist'] =  false;
$wgGroupPermissions['*']['viewmyprivateinfo'] =  false;
$wgGroupPermissions['*']['viewmywatchlist'] =  false;
$wgGroupPermissions['*']['skipcaptcha'] = false;


#user not confirmed
$wgGroupPermissions['user']['oathauth-enable'] = true;
$wgGroupPermissions['user']['edit'] =  false;
$wgGroupPermissions['user']['createpage'] =  false;
$wgGroupPermissions['user']['changetags'] =  false;
$wgGroupPermissions['user']['applychangetags'] =  false;
$wgGroupPermissions['user']['createtalk'] =  false;
$wgGroupPermissions['user']['editcontentmodel'] =  false;
$wgGroupPermissions['user']['move'] =  false;
$wgGroupPermissions['user']['upload'] =  false;
$wgGroupPermissions['user']['editmyusercss'] =  true;
$wgGroupPermissions['user']['editmyuserjson'] =  true;
$wgGroupPermissions['user']['editmyuserjs'] =  true;
$wgGroupPermissions['user']['editmyuserjsredirect'] =  true;
$wgGroupPermissions['user']['minoredit'] =  false;
$wgGroupPermissions['user']['move-categorypages'] =  false;
$wgGroupPermissions['user']['movefile'] =  false;
$wgGroupPermissions['user']['move-subpages'] =  false;
$wgGroupPermissions['user']['move-rootuserpages'] =  false;
$wgGroupPermissions['user']['reupload-shared'] =  false;
$wgGroupPermissions['user']['reupload'] =  false;
$wgGroupPermissions['user']['sendemail'] =  false;
$wgGroupPermissions['user']['upload'] =  false;
$wgGroupPermissions['user']['skipcaptcha'] = false;
$wgGroupPermissions['user']['editmyprivateinfo'] =  true;
$wgGroupPermissions['user']['editmywatchlist'] =  true;
$wgGroupPermissions['user']['viewmyprivateinfo'] =  true;
$wgGroupPermissions['user']['viewmywatchlist'] =  true;
$wgGroupPermissions['user']['editmyoptions'] =  true;


#verified
$wgGroupPermissions['verified']['edit'] = true;
$wgGroupPermissions['verified']['createpage'] =  true;
$wgGroupPermissions['verified']['changetags'] =  true;
$wgGroupPermissions['verified']['applychangetags'] =  true;
$wgGroupPermissions['verified']['createtalk'] =  true;
$wgGroupPermissions['verified']['editcontentmodel'] =  true;
$wgGroupPermissions['verified']['move'] =  true;
$wgGroupPermissions['verified']['upload'] =  true;
$wgGroupPermissions['verified']['editmyoptions'] =  true;
$wgGroupPermissions['verified']['editmyusercss'] =  true;
$wgGroupPermissions['verified']['editmyuserjson'] =  true;
$wgGroupPermissions['verified']['editmyuserjs'] =  true;
$wgGroupPermissions['verified']['editmyuserjsredirect'] =  true;
$wgGroupPermissions['verified']['minoredit'] =  true;
$wgGroupPermissions['verified']['movefile'] =  true;
$wgGroupPermissions['verified']['move-subpages'] =  true;
$wgGroupPermissions['verified']['reupload-shared'] =  true;
$wgGroupPermissions['verified']['reupload'] =  true;
$wgGroupPermissions['verified']['sendemail'] =  true;
$wgGroupPermissions['verified']['editmyprivateinfo'] =  true;
$wgGroupPermissions['verified']['editmywatchlist'] =  true;
$wgGroupPermissions['verified']['viewmyprivateinfo'] =  true;
$wgGroupPermissions['verified']['viewmywatchlist'] =  true;
$wgGroupPermissions['verified']['upload'] =  true;
$wgGroupPermissions['verified']['writeapi'] = true;
$wgGroupPermissions['verified']['translate'] = true;
$wgGroupPermissions['verified']['translate-messagereview'] = true;
$wgGroupPermissions['verified']['translate-groupreview'] = true;
$wgGroupPermissions['verified']['translate-import'] = true;
$wgGroupPermissions['verified']['skipcaptcha'] = false;

#Trusted
$wgGroupPermissions['trusted'] =$wgGroupPermissions['verified'];
$wgGroupPermissions['trusted']['patchnotes'] =  true;
$wgGroupPermissions['trusted']['templates'] =  true;
$wgGroupPermissions['trusted']['move-rootuserpages'] =  true;
$wgGroupPermissions['trusted']['move-categorypages'] =  true;

#bureaucrat
$wgGroupPermissions['bureaucrat'] =$wgGroupPermissions['sysop'];
$wgGroupPermissions['bureaucrat']['usermerge'] = true;
$wgGroupPermissions['bureaucrat']['checkuser'] = true;
$wgGroupPermissions['bureaucrat']['userrights'] = true;
$wgGroupPermissions['bureaucrat']['checkuser-log'] = true;
$wgGroupPermissions['bureaucrat']['investigate'] = true;
$wgGroupPermissions['bureaucrat']['checkuser-temporary-account'] = true;
$wgImplicitGroups[] = 'bureaucrat';

#sys op
$wgGroupPermissions['sysop'] =$wgGroupPermissions['trusted'];
$wgGroupPermissions['sysop']['checkuser-log'] = true;
$wgGroupPermissions['sysop']['investigate'] = true;
$wgGroupPermissions['sysop']['checkuser-temporary-account'] = true;
$wgGroupPermissions['sysop']['sboverride'] = true;
$wgGroupPermissions['sysop']['pagetranslation'] = true;
$wgGroupPermissions['sysop']['translate-manage'] = true;
$wgGroupPermissions['sysop']['editinterface'] = true;
$wgGroupPermissions['sysop']['skipcaptcha'] = true;
$wgGroupPermissions['sysop']['cleantalk-bypass'] = true;


$wgGroupPermissions['bot']['cleantalk-bypass'] = true;
$wgGroupPermissions['bot']['skipcaptcha'] = true;

$wgAutopromote['verified'] = APCOND_EMAILCONFIRMED;

#################
#//*     DEV
#################
$wgShowExceptionDetails = $_ENV['WIKI_DEV'] ?? 'false';

#################
#//*    SPAM
#################
$wgCTAccessKey = "{$_ENV['WIKI_CT_KEY']}";
$wgCTMinEditCount = 10;
$wgCTShowLink = false;
#$wgSpamRegex = ["/online-casino|casino|buy-viagra|adipex|phentermine|lidocaine|milf|adult-website\.com|display:none|overflow:\s*auto;\s*height:\s*[0-4]px;/i"];
$wgCaptchaClass = 'ReCaptchaNoCaptcha';
$wgReCaptchaSiteKey = "{$_ENV['WIKI_CAP_KEY']}";
$wgReCaptchaSecretKey = "{$_ENV['WIKI_CAP_SECRET']}";
$wgCaptchaTriggers['edit'] = true;
$wgCaptchaTriggers['create'] = true;
$wgCaptchaTriggers['addurl'] = true;
$wgCaptchaTriggers['createaccount'] = true;
$wgCaptchaTriggers['badlogin'] = true;
$wgAllowConfirmedEmail = true;
$wgBlacklistSettings = [
	'spam' => [
		'files' => [
			"https://meta.wikimedia.org/w/index.php?title=Spam_blacklist&action=raw&sb_ver=1",
			"https://en.wikipedia.org/w/index.php?title=MediaWiki:Spam-blacklist&action=raw&sb_ver=1"
		],
	],
];
$wgEnableDnsBlacklist = true;
$wgDnsBlacklistUrls = array( 'xbl.spamhaus.org', 'dnsbl.tornevall.org' );
$wgSmiteSpamIgnoreSmallPages = false;

#################
#//*    CDN
#################
$wgUseCdn = true;
$wgUsePrivateIPs = true;
$wgCdnServersNoPurge = ['10.0.0.0/8',	'173.245.48.0/20',	'103.21.244.0/22',	'103.22.200.0/22',	'103.31.4.0/22',	'141.101.64.0/18',	'108.162.192.0/18',	'190.93.240.0/20',	'188.114.96.0/20',	'197.234.240.0/22',	'198.41.128.0/17',	'162.158.0.0/15',	'104.16.0.0/13',	'104.24.0.0/14',	'172.64.0.0/13',	'131.0.72.0/22',	'2400:cb00::/32',	'2606:4700::/32',	'2803:f800::/32',	'2405:b500::/32',	'2405:8100::/32',	'2a06:98c0::/29',	'2c0f:f248::/32',  '2405:b500::/32'];
$wgMultiPurgeEnabledServices = array ( 'Cloudflare' );
$wgMultiPurgeServiceOrder = array ( 'Cloudflare' );
$wgMultiPurgeCloudFlareZoneId = "{$_ENV['WIKI_CF_ID']}";
$wgMultiPurgeCloudflareApiToken = "{$_ENV['WIKI_CF_API']}";

#################
#//*    Cookies
#################
$wgCookieWarningEnabled = true;
$wgCookieSecure = true;
$wgCookieSameSite = 'Strict';

$wgDismissableSiteNoticeForAnons = true;

#################
#//*    CSP
#################
$wgReferrerPolicy = array('strict-origin-when-cross-origin', 'strict-origin');
$wgCSPHeader = [
  'useNonces' => true,
  'unsafeFallback' => false,
  'default-src' => ['\'self\'', 'https://www.recaptcha.net', 'https://analytics.dsp-wiki.com'],
  'script-src' => [ '\'self\'', '\'sha256-fZolVpA0hfg4qTFqcgfmgUvHzo0qL28/odWGiD5Bc7U=\'', 'https://analytics.dsp-wiki.com'],
	'style-src' => [ '\'self\''],
	'object-src' => [ '\'none\'' ],
];

#################
#//*    Json
#################
$wgJsonConfigEnableLuaSupport = true;
$wgJsonConfigModels['Tabular.JsonConfig'] = 'JsonConfig\JCTabularContent';
$wgJsonConfigs['Tabular.JsonConfig'] = [
        'namespace' => 486,
        'nsName' => 'Data',
        'pattern' => '/.\.tab$/',
        'license' => 'CC0-1.0',
        'isLocal' => false,
];
$wgJsonConfigModels['Map.JsonConfig'] = 'JsonConfig\JCMapDataContent';
$wgJsonConfigs['Map.JsonConfig'] = [
        'namespace' => 486,
        'nsName' => 'Data',
        'pattern' => '/.\.map$/',
        'license' => 'CC0-1.0',
        'isLocal' => false,
];
$wgJsonConfigInterwikiPrefix = "commons";
$wgJsonConfigs['Tabular.JsonConfig']['remote'] = [
        'url' => 'https://commons.wikimedia.org/w/api.php'
];
$wgJsonConfigs['Map.JsonConfig']['remote'] = [
        'url' => 'https://commons.wikimedia.org/w/api.php'
];

# DynamicPageList3
$wgDplSettings['recursiveTagParse'] = true;
$wgDplSettings['allowUnlimitedResults'] = true;

# PageImages
$wgPageImagesNamespaces = [ NS_MAIN, NS_PATCH_NOTES, NS_MODDING];
$wgPageImagesOpenGraphFallbackImage = "$wgResourceBasePath/images/DSP_Logo.png";

$wgPopupsReferencePreviewsBetaFeature = false;

$wgTwoColConflictBetaFeature = false;

# WebP
$wgWebPAutoFilter = true;
$wgWebPConvertInJobQueue = true;
$wgWebPEnableConvertOnUpload = true;
$wgWebPCompressionQuality = 95;

# WikiSEO
$wgTwitterSiteHandle = 'DSPWiki';
$wgWikiSeoDefaultLanguange = 'en-us';
$wgWikiSeoEnableSocialImages = true;
#Disable wgLogo as fallback image
$wgWikiSeoDisableLogoFallbackImage = true;
#TextExtracts description for SEO
$wgWikiSeoEnableAutoDescription = true;
$wgWikiSeoTryCleanAutoDescription = true;

$wgObjectCaches['redis'] = [
  'class'                => 'RedisBagOStuff',
  'servers'              => [ "{$_ENV['WIKI_REDIS_SERVER']}"],
  // 'connectTimeout'    => 1,
  // 'persistent'        => false,
  // 'password'          => 'secret',
  // 'automaticFailOver' => true,
];
$wgJobTypeConf['default'] = [
  'class' => 'JobQueueRedis',
  'order' => 'fifo',
  'redisServer' => $_ENV['WIKI_REDIS_SERVER'],
  'checkDelay' => true,
  'daemonized' => true
];
$wgJobRunRate = 0;

$wgNamespaceAliases['T'] = NS_TEMPLATE;
$wgNamespaceAliases['PN'] = NS_PATCH_NOTES;
$wgNamespaceAliases['PATCH'] = NS_PATCH_NOTES;
$wgNamespaceAliases['U'] = NS_PATCH_NOTES;
