<?php namespace ProcessWire;

class AdminStyleKonkat extends WireData implements Module {

	public static function getModuleInfo() {
		return array(
			'title' => 'Admin Style Konkat',
			'version' => 1,
			'summary' => 'Improved Konkat styling for AdminThemeUikit.',
			'autoload' => 'template=admin',
			'singular' => true,
			'requires' => array('PHP>=7.4.0', 'AdminThemeUikit'),
		);
	}

	public function init() {
		$this->addHookAfter('AdminThemeUikit::renderFile', $this, 'replaceStyles');
	}

	/**
	 * Replace the default Konkat stylesheet and remove the redundant base styles.
	 *
	 * After AdminThemeUikit renders _head.php for themeName=default, replace its
	 * themes/default/admin.css link in place with this module's admin.css in debug
	 * mode, or admin.min.css otherwise. Add the selected file's modification time
	 * to its URL as a cache key. Remove duplicate matching Konkat links and the
	 * links to getUikitCSS() (normally pw.min.css) and core
	 * wire/templates-admin/styles/AdminTheme.css, whose styles are in our bundle.
	 *
	 * Use regex link-tag replacements matching the supplied URLs, allowing query
	 * strings and fragments. Keep admin-custom.css, user CSS, module styles and
	 * scripts unchanged. Leave all output unchanged if the replacement bundle
	 * is missing or empty, or the original Konkat stylesheet link is absent.
	 *
	 * @param HookEvent $event After-hook event whose return value contains the rendered head markup.
	 * @return void
	 */
	public function replaceStyles(HookEvent $event) {
		if(basename($event->arguments(0)) !== '_head.php') return;
		$theme = $event->object;
		if($theme->get('themeName') !== 'default') return;
		$file = $this->wire()->config->debug ? 'admin.css' : 'admin.min.css';
		$path = $this->wire()->config->paths->get($this->className()) . $file;
		if(!is_file($path) || !filesize($path)) return;
		$url = $this->wire()->config->urls->get($this->className()) . $file . '?v=' . filemtime($path);
		$themeInfo = $theme->getThemeInfo();
		$baseUrls = array(
			$theme->getUikitCSS(),
			$this->wire()->config->urls->root . 'wire/templates-admin/styles/AdminTheme.css',
		);
		$html = $event->return;
		$linkPattern = function(array $urls) {
			$paths = array_map(function($url) {
				return preg_quote(strtok($url, '?#'), '#');
			}, $urls);
			return '#<link\b[^>]*\bhref=[\'"](?:' . implode('|', $paths) . ')(?:[?\#][^\'"]*)?[\'"][^>]*>#i';
		};
		$inserted = false;
		$result = preg_replace_callback($linkPattern(array($themeInfo['url'] . 'admin.css')), function() use ($url, &$inserted) {
			if($inserted) return '';
			$inserted = true;
			return '<link rel="stylesheet" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
		}, $html);
		if(!$inserted) return;
		$event->return = preg_replace($linkPattern($baseUrls), '', $result);
	}
}
