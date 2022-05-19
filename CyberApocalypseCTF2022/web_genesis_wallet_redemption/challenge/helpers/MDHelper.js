const showdown        = require('showdown')
const createDOMPurify = require('dompurify');
const { JSDOM }       = require('jsdom');

const conv = new showdown.Converter({
	completeHTMLDocument: false,
	tables: true,
	ghCodeBlocks: true,
	simpleLineBreaks: true,
	strikethrough: true,
	metadata: false,
	emoji: true
});

const makeHtml = (md) => {
    return(conv.makeHtml(md));
}

const filterHTML = (content) => {
    html = makeHtml(content);
    window = new JSDOM('').window;
    DOMPurify = createDOMPurify(window);
    return DOMPurify.sanitize(html, {ALLOWED_TAGS: ['strong', 'em', 'img', 'a', 's', 'ul', 'ol', 'li']});
}

module.exports = {
	filterHTML
};