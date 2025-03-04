<?php

namespace App\Infrastructure\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Renderer\Block\HeadingRenderer;
use League\CommonMark\Extension\CommonMark\Renderer\Block\ListBlockRenderer;
use League\CommonMark\Extension\CommonMark\Renderer\Block\ListItemRenderer;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\LinkRenderer;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Renderer\HtmlRenderer;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Extension\CommonMark\Renderer\Block\ParagraphRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Extension\CommonMark\Renderer\Block\BlockQuoteRenderer;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\CodeRenderer;

class MarkdownService
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $config = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ];
        
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        
        // Register custom renderers for styling
        $environment->addRenderer(Paragraph::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                $paragraph = new HtmlElement(
                    'p',
                    ['class' => 'text-gray-700 mb-4'],
                    $childRenderer->renderNodes($node->children())
                );
                return $paragraph;
            }
        });
        
        $environment->addRenderer(Heading::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                $heading = $node;
                $level = $heading->getLevel();
                $content = $childRenderer->renderNodes($heading->children());
                
                $classes = match ($level) {
                    1 => 'text-3xl font-bold text-gradient mb-6 mt-8',
                    2 => 'text-3xl font-bold text-gradient mt-8 mb-6',
                    3 => 'text-2xl font-bold mt-6 mb-3',
                    4 => 'text-xl font-bold mt-5 mb-2',
                    default => 'font-bold mt-4 mb-2',
                };
                
                return new HtmlElement(
                    'h' . $level,
                    ['class' => $classes],
                    $content
                );
            }
        });
        
        $environment->addRenderer(ListBlock::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                $listBlock = $node;
                $tag = $listBlock->getListData()->type === ListBlock::TYPE_BULLET ? 'ul' : 'ol';
                $classes = $tag === 'ul' ? 'list-disc pl-8 mb-6 text-gray-700' : 'list-decimal pl-8 mb-6 text-gray-700';
                
                return new HtmlElement(
                    $tag,
                    ['class' => $classes],
                    $childRenderer->renderNodes($node->children())
                );
            }
        });
        
        $environment->addRenderer(ListItem::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                return new HtmlElement(
                    'li',
                    ['class' => 'mb-2'],
                    $childRenderer->renderNodes($node->children())
                );
            }
        });
        
        $environment->addRenderer(Link::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                $link = $node;
                $attrs = $link->data->get('attributes', []);
                $attrs['href'] = $link->getUrl();
                $attrs['class'] = 'text-blue-600 hover:text-blue-800 underline';
                
                return new HtmlElement(
                    'a',
                    $attrs,
                    $childRenderer->renderNodes($link->children())
                );
            }
        });
        
        $environment->addRenderer(BlockQuote::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                return new HtmlElement(
                    'blockquote',
                    ['class' => 'border-l-4 border-gray-300 pl-4 italic my-4'],
                    $childRenderer->renderNodes($node->children())
                );
            }
        });
        
        $environment->addRenderer(Code::class, new class implements NodeRendererInterface {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                $code = $node;
                return new HtmlElement(
                    'code',
                    ['class' => 'bg-gray-100 rounded px-1 py-0.5 font-mono text-sm'],
                    $code->getLiteral()
                );
            }
        });
        
        $this->converter = new MarkdownConverter($environment);
    }

    public function convertToHtml(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }
}