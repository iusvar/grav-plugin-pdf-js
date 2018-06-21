# Pdf-js Plugin

The **Pdf-js** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It allows you to embed [PDF.js](https://mozilla.github.io/pdf.js/) into pages via shortcode.

For a demo, [visit my blog](http://iusvar.alwaysdata.net/grav/pdfjs).

## Installation

Installing the Pdf-js plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install pdf-js

This will install the Pdf-js plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/pdf-js`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `pdf-js`. You can find these files on [GitHub](https://github.com/severo-iuliano/grav-plugin-pdf-js) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/pdf-js
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error), [Problems](https://github.com/getgrav/grav-plugin-problems) and [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) plugins to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/pdf-js/pdf-js.yaml` to `user/config/plugins/pdf-js.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
height: 300
```

Note that if you use the admin plugin, a file with your configuration, and named pdf-js.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

This plugin uses the [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) infrastructure. Read those docs for the nitty gritty of how shortcodes work.

The Pdf-js shortcode is a self-closing `[pdfjs option1="value1" option2="value2" ... /]`, and it accepts the following options:

* `file` is the only required parameter. It points to the datafile you wish to load. By default, the plugin looks in the same folder as the page file. This is adequate for most usage. You can also load files from the `user/data` folder by prefixing your file name with `data:` (e.g., `file=data:folder/filename.pdf`). 

  If all you're passing is the file name, then you can shorten the code to the form `[pdfjs=filename.pdf/]`.

  FYI, slash is not essential.

### Example Codes

* `[pdfjs=filename.pdf]` or `[pdfjs file=filename.pdf]` (basic embed of pdf file in the same folder as the page itself)

* `[pdfjs=data:filename.pdf]` or `[pdfjs file=data:filename.pdf]` (basic embed of pdf file in the `user/data` folder)

* `[pdfjs=data:subdir/filename.pdf]` or `[pdfjs file=data:subdir/filename.pdf]` (basic embed of pdf file in the `user/data/subdir` folder)

> Note: `data` can take any name, for example: `pdfs` or `repository` or whatever you like.

> Note: if the file name contains spaces use double quotes, for example: [pdfjs="first filename.pdf"] or [pdfjs file="first filename.pdf"] or [pdfjs="data:filename.pdf"] and so on.

## Credits

Pdf-js is a general-purpose, web standards-based platform for parsing and rendering PDFs of Mozilla. More informations are available here:  [https://mozilla.github.io/pdf.js/](https://mozilla.github.io/pdf.js/)

## To Do

- [ ] Reduce the size of the library.

- [ ] Make the toolbar buttons available via Admin.

- [ ] Solve this warning if possible: `Warning: Setting up fake worker`.
