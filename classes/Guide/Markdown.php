<?php

namespace Phalcana\Guide;

use Michelf\MarkdownExtra;


/**
 * Documentation helper
 *
 * [!!] Remember the milk!
 *
 * @package     Userguide
 * @category    Services
 * @author      Neil Brayfield
 */
class Markdown extends MarkdownExtra
{

    /**
     * @var  string  base url for links
     */
    public static $instance;

    /**
     * @var  string  base url for links
     */
    public static $baseUrl = '';

    /**
     * @var  string  base url for images
     */
    public static $imageUrl = '';

    /**
     * Currently defined heading ids.
     * Used to prevent creating multiple headings with same id.
     *
     * @var  array
     */
    protected $heading_ids = array();

    /**
     * Insert function calls inbetween the standard markdown calls
     *
     * @return  void
     */
    public function __construct()
    {
        // doImage is 10, add image url just before
        $this->span_gamut['doImageURL'] = 9;

        // doLink is 20, add base url just before
        $this->span_gamut['doBaseURL'] = 19;

        // Add API links
        $this->span_gamut['doAPI'] = 90;

        // Add note spans last
        $this->span_gamut['doNotes'] = 100;

        // PHP4 makes me sad.
        parent::__construct();
    }


    /**
     * Add the current base url to all local links.
     *
     *     [filesystem](about.filesystem "Optional title")
     *
     * @param   string  Span text
     * @return  string
     */
    public function doBaseURL($text)
    {
        // URLs containing "://" are left untouched
        return preg_replace(
            '~(?<!!)(\[.+?\]\()(?!\w++://)(?!#)(\S*(?:\s*+".+?")?\))~',
            '$1'.self::$baseUrl.'$2',
            $text
        );
    }


    /**
     * Add the current base url to all local images.
     *
     *     ![Install Page](img/install.png "Optional title")
     *
     * @param   string  Span text
     * @return  string
     */
    public function doImageURL($text)
    {
        // URLs containing "://" are left untouched
        return preg_replace('~(!\[.+?\]\()(?!\w++://)(\S*(?:\s*+".+?")?\))~', '$1'.self::$imageUrl.'$2', $text);
    }

    /**
     * Add the link the API browser
     *
     * @return  string
     * @author  Neil Brayfield
     **/
    public function doAPI($url)
    {
        return $url;
    }

    /**
     * Wrap notes in the applicable markup. Notes can contain single newlines.
     *
     *     [!!] Remember the milk!
     *
     * @param   string  Span text
     * @return  string
     */
    public function doNotes($text)
    {
        if (!preg_match('/^\[!!\]\s*+(.+?)(?=\n{2,}|$)/s', $text, $match)) {
            return $text;
        }

        return $this->hashBlock('<p class="note">'.$match[1].'</p>');
    }
}
