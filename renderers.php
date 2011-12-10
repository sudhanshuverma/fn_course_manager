<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
class block_course_menu_core_renderer extends core_renderer {
    
        /**
     * Start output by sending the HTTP headers, and printing the HTML <head>
     * and the start of the <body>.
     *
     * To control what is printed, you should set properties on $PAGE. If you
     * are familiar with the old {@link print_header()} function from Moodle 1.9
     * you will find that there are properties on $PAGE that correspond to most
     * of the old parameters to could be passed to print_header.
     *
     * Not that, in due course, the remaining $navigation, $menu parameters here
     * will be replaced by more properties of $PAGE, but that is still to do.
     *
     * @return string HTML that you must output this, preferably immediately.
     */
    public function header() {
        global $USER, $CFG;

        if (session_is_loggedinas()) {
            $this->page->add_body_class('userloggedinas');
        }

        $this->page->set_state(moodle_page::STATE_PRINTING_HEADER);

        // Find the appropriate page layout file, based on $this->page->pagelayout.
        $layoutfile = $this->page->theme->layout_file($this->page->pagelayout);
        // Render the layout using the layout file.
        $rendered = $this->render_page_layout($layoutfile);

        // Slice the rendered output into header and footer.
        $cutpos = strpos($rendered, self::MAIN_CONTENT_TOKEN);
        if ($cutpos === false) {
            throw new coding_exception('page layout file ' . $layoutfile .
                    ' does not contain the string "' . self::MAIN_CONTENT_TOKEN . '".');
        }
        $header = substr($rendered, 0, $cutpos);
        $footer = substr($rendered, $cutpos + strlen(self::MAIN_CONTENT_TOKEN));

        if (empty($this->contenttype)) {
            debugging('The page layout file did not call $OUTPUT->doctype()');
            $header = $this->doctype() . $header;
        }

        send_headers($this->contenttype, $this->page->cacheable);

        $this->opencontainers->push('header/footer', $footer);
        $this->page->set_state(moodle_page::STATE_IN_BODY);
        //code added by sudhanshu
        if (file_exists("$CFG->dirroot/blocks/course_menu/tab.php") ){
            require_once("$CFG->dirroot/blocks/course_menu/tab.php");                                 
        } else{            
            $tabss ="";            
        }
        //code added by sudhanshu end here
        return $header.$tabss.$this->skip_link_target('maincontent');
    }
    
  

}
    
 

?>
