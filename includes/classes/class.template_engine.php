<?php
/********************************************************
 * Template Engine
 * Class for templatating a script
********************************************************
 * @package Template Engine
 * @author Craig Merchant
 * @copyright 20016-17 Bylancer
 ********************************************************/

class HtmlTemplate 
{
	// The template file being used
	var $template;
	
	// The html content of the template
	var $html;
	
	// The parameters to be replaced
	var $parameters = array();
	
	
	/*************************************************************************
	* function HtmlTemplate ($template)
	* Reads the file into a string variable
	* USAGE example:
	* $template = 'html/index.html';
	* $page = new HtmlTemplate ($template);
	*************************************************************************
	* $template: The file you want to convert
	* ***********************************************************************/
	function __construct($template)
	{
		$this->template = $template;
		$this->html = implode ('', (file($this->template)));
	}
	
	/*************************************************************************
	* function SetLoop ($name, $values)
	* Outputs an array as an html loop
	* USAGE example:
	* $items['0']['title'] = 'A Book';
	* $items['0']['id'] = '1';
	* $page->SetLoop ("ITEMS", $items);
	*************************************************************************
	* $name: The name of the Loop
	* $values: The Array
	* ***********************************************************************/
	function SetLoop ($name, $values)
	{
		$return = '';
		if(preg_match('/{LOOP: ' . $name . '\}(.*)\{\/LOOP: ' . $name . '\}/s', $this->html, $parts))
		{
			if(is_array($values))
			{
				foreach ($values as $value) 
				{
					$templatestuff = $parts['1'];
					foreach ($value as $key2 => $value2) 
					{
						if(!is_array($value2))
						{
							$templatestuff = str_replace('{' . $name . '.' . $key2 . '}', $value2, $templatestuff);			
						}
						else
						{
							foreach ($value2 as $key3 => $value3) 
							{
								if(!is_array($value3))
								{
									$templatestuff = str_replace('{' . $name . '.' . $key2 . '/' . $key3 . '}', $value3, $templatestuff);
								}
								else
								{
									foreach ($value3 as $key4 => $value4) 
									{
										if(!is_array($value4))
										{
											$templatestuff = str_replace('{' . $name . '.' . $key2 . '/' . $key3 . '/' . $key4 . '}', $value4, $templatestuff);
										}
									}
								}
							}
						}
					}
					$return.= $templatestuff;
				}
			}
			else
			{
				$return = $values;
			}

            $this->html = stripslashes(str_replace ($parts['0'], $return, $this->html));
		}

	}

	/*************************************************************************
	* function SetParameter ($variable, $value)
	* Sets a paramateter to later be replaced in the template
	* USAGE example:
	* $page->SetParameter ("TITLE", 'The about page');
	*************************************************************************
	* $variable: The parameters name
	* $value: The paramaters value
	* ***********************************************************************/
	function SetParameter ($variable, $value)
	{
		$this->parameters[$variable] = $value;
	}

	/*************************************************************************
	* function CreatePageEcho () 
	* Echos the finished template
	* USAGE example:
	* $page->CreatePageEcho();
	* ***********************************************************************/
	function CreatePageEcho ($lang,$config,$link)
	{
		foreach ($lang as $key => $value) 
		{
			$template_name = '{LANG_' . $key . '}';
			$this->html = str_replace ($template_name, $value, $this->html);
		}	
		foreach ($this->parameters as $key => $value) 
		{
			$template_name = '{' . $key . '}';

			$this->html = str_replace ($template_name, $value, $this->html);
		}

        foreach ($link as $key => $value)
        {
            $template_name = '{LINK_' . $key . '}';
            $this->html = str_replace ($template_name, $value, $this->html);
        }


		$this->html = str_replace ('{SITE_URL}', $config['site_url'], $this->html);
		$this->html = str_replace ('{TPL_NAME}', $config['tpl_name'], $this->html);
        $this->html = str_replace ('{SPECIFIC_COUNTRY}', $config['specific_country'], $this->html);
        $this->html = str_replace ('{CURRENCY_SIGN}', $config['currency_sign'], $this->html);
        $this->html = str_replace ('{CURRENCY_CODE}', $config['currency_code'], $this->html);
        $this->html = str_replace ('{CURRENCY_POS}', $config['currency_pos'], $this->html);

		if(isset($config['temp_php']))
		{
			if($config['temp_php'])
			{
				$this->html = preg_replace_callback("/(<\?=)(.*?)\?>/si", array('HtmlTemplate', 'EvalPrintBuffer'),$this->html);
				$this->html = preg_replace_callback("/(<\?php|<\?)(.*?)\?>/si", array('HtmlTemplate', 'EvalBuffer'),$this->html);
			}
		}

		$ifmatches = array();
		preg_match_all('/IF\(\"(.*?)\"(.*?)\"(.*?)\"\)\{(.*?)\{:IF\}/s', $this->html, $ifmatches);
		
		if(count($ifmatches['0']) != 0)
		{	
			foreach ($ifmatches['0'] as $key => $value) 
			{
				if(trim($ifmatches['2'][$key]) == '!=')
				{
					if($ifmatches['1'][$key] != $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '==')
				{
					if($ifmatches['1'][$key] == $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '<')
				{
					if($ifmatches['1'][$key] < $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '>')
				{
					if($ifmatches['1'][$key] > $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '%')
				{
					$mod = $ifmatches['1'][$key]%$ifmatches['3'][$key];

					if($mod == 0)
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
			}
		}
			
		echo $this->html;
	}
	
	/*************************************************************************
	* function CreatePageReturn () 
	* Returns the finished template as a string
	* USAGE example:
	* $head_string = $header->CreatePageReturn($lang);
	*************************************************************************
	* $lang: The language list
	* ***********************************************************************/
	function CreatePageReturn ($lang,$config,$link)
	{
		foreach ($lang as $key => $value) 
		{
			$template_name = '{LANG_' . $key . '}';
			$this->html = str_replace ($template_name, $value, $this->html);
		}
        if (is_array($link) || is_object($value))
        {
            foreach ($link as $key => $value)
            {
                $template_name = '{LINK_' . $key . '}';
                $this->html = str_replace ($template_name, $value, $this->html);
            }
        }

		foreach ($this->parameters as $key => $value) 
		{
			$template_name = '{' . $key . '}';

			$this->html = str_replace ($template_name, $value, $this->html);
		}	
		
		$this->html = str_replace ('{SITE_URL}', $config['site_url'], $this->html);
		$this->html = str_replace ('{TPL_NAME}', $config['tpl_name'], $this->html);
		
		if(isset($config['temp_php']))
		{
			if($config['temp_php'])
			{
				$this->html = preg_replace_callback("/(<\?=)(.*?)\?>/si", array('HtmlTemplate', 'EvalPrintBuffer'),$this->html);
				$this->html = preg_replace_callback("/(<\?php|<\?)(.*?)\?>/si", array('HtmlTemplate', 'EvalBuffer'),$this->html);
			}
		}

		$ifmatches = array();
		preg_match_all('/IF\(\"(.*?)\"(.*?)\"(.*?)\"\)\{(.*?)\{:IF\}/s', $this->html, $ifmatches);
		
		if(count($ifmatches['0']) != 0)
		{	
			foreach ($ifmatches['0'] as $key => $value) 
			{
				if(trim($ifmatches['2'][$key]) == '!=')
				{
					if($ifmatches['1'][$key] != $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '==')
				{
					if($ifmatches['1'][$key] == $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '<')
				{
					if($ifmatches['1'][$key] < $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '>')
				{
					if($ifmatches['1'][$key] > $ifmatches['3'][$key])
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
				elseif(trim($ifmatches['2'][$key]) == '%')
				{
					$mod = $ifmatches['1'][$key]%$ifmatches['3'][$key];

					if($mod == 0)
					{
						$this->html = str_replace($value, $ifmatches['4'][$key], $this->html);
					}
					else
					{
						$this->html = str_replace($value, '', $this->html);
					}
				}
			}
		}
		
		return $this->html;
	}
	
	function EvalBuffer($string) 
	{
		ob_start();
		eval("$string[2];");
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}
	
	function EvalPrintBuffer($string) 
	{
		ob_start();
		eval("print $string[2];");
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}
}
?>