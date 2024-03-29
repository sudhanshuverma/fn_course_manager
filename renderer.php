<?php
/*
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * This file is part of the Course Menu block for Moodle
 *
 * The Course Menu block for Moodle software package is Copyright � 2008 onwards NetSapiensis AB and is provided under
 * the terms of the GNU GENERAL PUBLIC LICENSE Version 3 (GPL). This program is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version. This program is distributed in the hope that
 * it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.
 *
 * See the GNU General Public License for more details. You should have received a copy of the GNU General Public
 * License along with this program.
 * If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------------------------------------------------------
 */


class block_course_menu_renderer extends core_renderer {
	
	private $topic_depth = 1;
	private $chapter_depth = 2;
	private $subchater_depth = 3;
	public $session;
	private $displaysection = 1000;
    	
	public function render_chapter_tree($instance, $config, $chapters, $sections, $displaysection, $hr = false)
	{
        $this->displaysection = $displaysection;
        
		$this->session = $_SESSION['cm_tree'][$instance]['expanded_elements'];
               
		if ($config->chapEnable) {
			$this->topic_depth++;
			if ($config->subChapEnable) {
				$this->topic_depth++;
			}
		}
		if ($config->expandableTree) {
			$this->topic_depth++;
		}
		$sectionIndex = 0;
		$contents = '';
		foreach ($chapters as $chapter) {
                    
			$subchapter = '';
			foreach ($chapter['childElements'] as $child) {
                            
				$topic = '';
				$cl = "";
				if ($child['type'] == 'subchapter') {
					for ($i = 0; $i < $child['count']; $i++) {
						$topic .= $this->render_topic($config, $sections[$sectionIndex], 0, $displaysection == $sectionIndex + 1);
						$sectionIndex++;
					}
					if ($config->subChapEnable) {                                            
						$title = html_writer::tag('span', $child['name'], array('class' => 'item_name'));
						$p = html_writer::tag('p', $title, array ('class' => 'cm_tree_item tree_item branch'));
						$topic = html_writer::tag('ul', $topic);
						$collapsed = "collapsed";
						if ($child['expanded']) {
							$collapsed = "";
						}
						$topic = html_writer::tag('li', $p . $topic, array('class' => "type_structure depth_{$this->subchater_depth} {$collapsed} contains_branch"));
					}
				} else { //topic
					$d = $this->topic_depth;
					if ($config->subChapEnable) {
						$d--;
					}
					$topic = $this->render_topic($config, $sections[$sectionIndex], $d, $displaysection == $sectionIndex + 1);
					$sectionIndex++;
				}
				$subchapter .= $topic;
			}
			//$subchapter - a collection of <li> elements
			if ($config->chapEnable) {
				$subchapter = html_writer::tag('ul', $subchapter);
				$title = html_writer::tag('span', $chapter['name'], array('class' => 'item_name'));
				$p = html_writer::tag('p', $title, array('class' => 'cm_tree_item tree_item branch'));
				$collapsed = "collapsed";
				if ($chapter['expanded']) {
					$collapsed = "";
				}
				$contents .= html_writer::tag('li', $p . $subchapter, array('class' => "type_structure depth_{$this->chapter_depth} {$collapsed} contains_branch"));
			} else {
				$contents .= $subchapter;
			}
		}
        if ($hr) {
            $contents = html_writer::tag('li', html_writer::empty_tag('hr')) . $contents;
        }
		return $contents;
	}
	
	public function render_topic($config, $section, $depth = 0, $current = false)
	{
                global $CFG, $USER, $OUTPUT, $DB, $PAGE, $COURSE;
                require_once($CFG->dirroot.'/course/lib.php');
                
                $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);              
                $isediting = $PAGE->user_is_editing() && has_capability('moodle/course:manageactivities', $context);
                $ismoving = ismoving($COURSE->id);
                
                $sections =get_all_sections($COURSE->id);                
                
                
                if ($ismoving) {
                        $strmovehere = get_string('movehere');
                        $strmovefull = strip_tags(get_string('movefull', '', "'$USER->activitycopyname'"));
                        $strcancel = get_string('cancel');
                        $stractivityclipboard = $USER->activitycopyname;
                 } 
		if ($depth == 0) {
			$depth = $this->topic_depth;
		}
                
		$html = '';
		if ($config->expandableTree) {                    
			foreach ($section['resources'] as $resource) {                           
				$visible_title = $resource['trimmed_name'];
				$attributes = array('title' => $resource['name']);
				$icon = $this->icon($resource['icon'], $resource['trimmed_name'], array('class' => 'smallicon navicon'));
				$html .= $this->render_leaf($visible_title, $icon, $attributes, $resource['url'], '', '','', $resource);
			}
                        if (!$html && $ismoving){
                           // foreach($sections as $sect){
                           //     if($sect->section == 0) continue;
                            
//                            /$updatedlink = "{$CFG->wwwroot}/course/mod.php?movetosection={$sect->id}&sesskey=".sesskey()."";                       
                            $html .= html_writer::link("",
                                        html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('movehere'),
                                        'height'=>'16px', 'width' =>'80px', 'border'=>'0px',
                                        'alt' => get_string('home'))),
                                        array('title' =>$strmovefull));
                          //  }
                            $html = html_writer::tag('ul', $html);
                        } else{
                            $html = html_writer::tag('ul', $html);                        
                        }                   
                        
			$title = html_writer::link($section['url'], $section['trimmed_name'], array('class' => 'item_name section_link'));
                        
			$cl = '';
			if ($current) {
				$cl = "active_tree_node";
			}
			$p = html_writer::tag('p', $title, array('class' => 'cm_tree_item tree_item branch ' . $cl));
			$collapsed = "collapsed";
			if ($section['expanded']) {
				$collapsed = "";
			}
			$append = "";
			if ($current) {
				$append = "current_branch";
			}                        
			$html = html_writer::tag('li', $p . $html, array('class' => "type_structure contains_branch depth_{$depth} {$collapsed} {$append}"));
			
		} else {
			$attributes = array('class' => 'section_link', 'title' => $section['name']);
			if (!$section['visible']) {
				$attributes['class'] .= 'dimmed_text';
			}
            if ($current) {
                $attributes['class'] .= ' active_tree_node';
            }
			$leafIcon = $this->icon($OUTPUT->pix_url('i/navigationitem'), $section['trimmed_name'], array('class' => 'smallicon'));
			
			$html = $this->render_leaf($section['trimmed_name'], $leafIcon, $attributes, $section['url'], $current); 	
		}
		
		return $html;
	}
	
	public function render_leaf($visible_title, $icon, $attributes, $link, $current = false, $extraNode = '', $hr = false, $resource = null)
	{       

                global $CFG, $USER, $OUTPUT, $DB, $PAGE;
                require_once($CFG->dirroot.'/course/lib.php');
                //original code
                $html = html_writer::link($link, $icon . $visible_title . $extraNode, $attributes);
		$html = html_writer::tag('p', $html, array ('class' => 'tree_item leaf hasicon'));
                $append = "";
                //original code
                
                $course = $this->page->course;
                
                if (isset($resource) && ($resource!= null)){
                    
                    $modid = $resource['id'];                    
                    $cm = get_coursemodule_from_id('', $modid, 0, false, MUST_EXIST);                    
                    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
                    get_all_mods($course->id, $mods, $modnames, $modnamesplural, $modnamesused);
                    $mod = $mods[$modid];
                    
                    $context = get_context_instance(CONTEXT_COURSE, $course->id);                
                    //$isediting = $this->page->user_is_editing() && has_capability('moodle/course:manageactivities', $context);
                    $isediting = $PAGE->user_is_editing() && has_capability('moodle/course:manageactivities', $context);
                    $groupbuttons     = ($course->groupmode or (!$course->groupmodeforce));
                    $groupbuttonslink = (!$course->groupmodeforce);                    
                    $groupbuttonslink = (!$course->groupmodeforce);
                    $ismoving = ismoving($course->id); 
                    $moving_and_editing = $isediting && ismoving($course->id);                    
                    
                    if ($ismoving) {
                        $strmovehere = get_string('movehere');
                        $strmovefull = strip_tags(get_string('movefull', '', "'$USER->activitycopyname'"));
                        $strcancel = get_string('cancel');
                        $stractivityclipboard = $USER->activitycopyname;
                    } 
                    
                    $editbuttons = '';
                    
                    if (!$ismoving || $isediting) {            
                        if ($groupbuttons) {                            
                            if (!$mod->groupmodelink = $groupbuttonslink) {
                                $mod->groupmode = $course->groupmode;
                            }

                        } else {
                            $mod->groupmode = false;
                        }                  
                    
                    } else {
                        $editbuttons = '';
                    }                    
                    
                    if ($mod->visible || has_capability('moodle/course:viewhiddenactivities', $context)) {
                        if ($ismoving) {                           
                            $updatedlink = "{$CFG->wwwroot}/course/mod.php?moveto={$mod->id}&sesskey=".sesskey()."";                       
                            $html .= html_writer::link($updatedlink,
                                        html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('movehere'),
                                        'height'=>'16px', 'width' =>'80px', 'border'=>'0px',
                                        'alt' => get_string('home'))),
                                        array('title' =>$strmovefull));
                        }
                    }
                    if (!$ismoving && $isediting) {                         
                        $editbuttons = make_editing_buttons($mod, true, true);   
                        echo '&nbsp;&nbsp;';
                        $html .= html_writer::tag('div', $editbuttons, array('class'=>'buttons'));                    
                    }
                    
                } 
               
		
		if ($current) {
			$append = "current_branch";
		}
            if ($hr) {
                $html = html_writer::empty_tag('hr') . $html;
            }
                    $html = html_writer::tag('li', $html, array ('class' => "type_custom item_with_icon {$append}"));
                    return $html;
	}
	
	public function icon($src, $title, $props = array())
	{
		$p = "";
		foreach ($props as $p => $v) {
			$p .= '"' . $p . '=' . $v . '" ';
		}
		return '<img src="' . $src . '" 
				class="smallicon" title="' . $title . '"
				alt="' . $title . '" ' . $p . ' />';
	}
	
	public function render_link($link, $course, $hr = false)
	{
		global $CFG;
        
		$url = $link['url'];
		if ($link['keeppagenavigation']) {
			$url = $CFG->wwwroot . "/blocks/course_menu/link_with_navigation.php?courseid={$course}&url={$link['url']}&name={$link['name']}";
		}
		$icon = '';
		if ($link['icon']) {
			$icon = $this->icon($link['icon'], $link['name'], array('class' => 'smallicon navicon'));
		}
        $attr = array();
        if ($link['target']) { // open in new window
            $attr['onclick'] = <<<HTML
window.open('{$url}', '{$link['name']}', 'resizable={$link['allowresize']},scrollbars={$link['allowscroll']},' + 
'directories={$link['showdirectorylinks']},location={$link['showlocationbar']},menubar={$link['showmenubar']},' + 
'toolbar={$link['showtoolbar']},status={$link['showstatusbar']},width={$link['defaultwidth']},height={$link['defaultheight']}'); return false;
HTML;
        }
		return $this->render_leaf($link['name'], $icon, $attr, $url, false, '', $hr);
	}
	
    public function render_navigation_node(navigation_node $node, $expansionlimit, $hr = false) 
    {
    	$content = '';
    	if ($node->children->count()) {
    		$content = $this->navigation_node($node->children, array('class' => ''), $expansionlimit);
	    	$span = html_writer::tag('span', $node->get_content(), array('class' => 'item_name'));
	    	$p = html_writer::tag('p', $span, array('class' => 'cm_tree_item tree_item branch'));
	    	$collapsed = "";
	    	if (!in_array(md5($node->get_content()), $this->session)) {
	    		$collapsed = "collapsed";
	    	}
            $app = '';
            if ($hr) {
                $app = html_writer::empty_tag('hr');
            }
	    	$content = html_writer::tag('li', $app . $p . $content, array('class' => "type_system depth_2 contains_branch {$collapsed}"));
    	}
    	return $content;
    }
    
    protected function navigation_node($items, $attrs = array(), $expansionlimit = null, $depth = 2) {

        // exit if empty, we don't want an empty ul element
        if (count($items) == 0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        foreach ($items as $item) {
        	if (!$item->display) {
                continue;
            }
            $content = $item->get_content();
            $title = $item->get_title();
            $isbranch = ($item->type !== $expansionlimit && ($item->children->count() > 0 || ($item->nodetype == navigation_node::NODETYPE_BRANCH && $item->children->count()==0 && (isloggedin() || $item->type <= navigation_node::TYPE_CATEGORY))));
            $hasicon = ((!$isbranch || $item->type == navigation_node::TYPE_ACTIVITY)&& $item->icon instanceof renderable);
            $item->prev_opened = in_array(md5($content), $this->session);
			if ($hasicon) {
                $icon = $this->output->render($item->icon);
                $content = $icon . $content; // use CSS for spacing of icons
            }
            if ($item->helpbutton !== null) {
                $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class'=>'clearhelpbutton'));
            }

            if ($content === '') {
                continue;
            }
			
            if ($item->action instanceof action_link) {
                //TODO: to be replaced with something else
                $link = $item->action;
                if ($item->hidden) {
                    $link->add_class('dimmed');
                }
                $content = $this->output->render($link);
            } else if ($item->action instanceof moodle_url) {
                $attributes = array('class' => 'item_name');
                if ($title !== '') {
                    $attributes['title'] = $title;
                }
                if ($item->hidden) {
                    $attributes['class'] = 'dimmed_text';
                }
                $content = html_writer::link($item->action, $content, $attributes);

            } else if (is_string($item->action) || empty($item->action)) {
                $attributes = array('class' => 'item_name');
                if ($title !== '') {
                    $attributes['title'] = $title;
                }
                if ($item->hidden) {
                    $attributes['class'] .= ' dimmed_text';
                }
                $content = html_writer::tag('span', $content, $attributes);
            }
            // this applies to the li item which contains all child lists too
            $liclasses = array($item->get_css_type(), 'depth_'.$depth);
            if (!$item->prev_opened && ($item->has_children() && (!$item->forceopen || $item->collapse))) {
                $liclasses[] = 'collapsed';
            }
            if ($isbranch) {
                $liclasses[] = 'contains_branch';
            } else if ($hasicon) {
                $liclasses[] = 'item_with_icon';
            }
            if ($item->isactive === true) {
                $liclasses[] = 'current_branch';
            }
            $liattr = array('class'=>join(' ',$liclasses));
            // class attribute on the div item which only contains the item content
            $divclasses = array('cm_tree_item', 'tree_item');
            if ($isbranch) {
                $divclasses[] = 'branch';
            } else {
                $divclasses[] = 'leaf';
            }
            if ($hasicon) {
                $divclasses[] = 'hasicon';
            }
            if (!empty($item->classes) && count($item->classes)>0) {
                $divclasses[] = join(' ', $item->classes);
            }
            $divattr = array('class'=>join(' ', $divclasses));
            if (!empty($item->id)) {
                $divattr['id'] = $item->id;
            }
            $content = html_writer::tag('p', $content, $divattr) . $this->navigation_node($item->children, array(), $expansionlimit, $depth+1);
            if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
                $content = html_writer::empty_tag('hr') . $content;
            }
            $content = html_writer::tag('li', $content, $liattr);
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::tag('ul', implode("\n", $lis));
        } else {
            return '';
        }
    }
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