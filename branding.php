/**
 * Simple application to handle a GET request of a pre-defined accepted type, match the request, 
 * and output the corresponding JSON string to the request.
 */

<?php namespace Leahy\John;

    class Brander
    {
		// decalare an array to hold our accepted brands which we'll pass over using a constructor
    	protected $accepted_brands = array();

        public function __construct($passed_brands)
        {
        	// call method to set our protected variable from what we were passed during instantiation
			$this->set_accepted_brands($passed_brands);
	    }
        
// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * checks to see if we were passed a GET request
 *
 * @access  protected
 * @return  bool		returns true if we were passed a GET request, false if we weren't
 */

        protected function check_for_request()
        {
        	// ensure we were we passed a GET request
			if(!isset($_GET['brand']))
			{
				// we do not have a GET request, return false
				return FALSE;
			}
			
			// we have a GET request, return true
			return TRUE;
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * displays the passed data or null if passed data is not set 
 *
 * @access  protected
 * @return  void  
 */

        protected function display_output($data_to_display)
        {
            // ensure we have data to display
        	if(!isset($data_to_display))
        	{
        		// passed data was not properly set/is empty, exit gracefully while displaying 'null'
	        	$this->graceful_end('null');
        	}
        	
        	// we have data to display, display it
        	echo $data_to_display;
        	
        	// we have displayed our data, time to end the application
        	$this->graceful_end();
        }
                
// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * encodes the passed data as JSON 
 *
 * @access  protected
 * @return  string		returns a string containing the JSON encoded value(s) that were passed to it  
 */

        protected function encode_row($row_to_encode)
        {
        	// encode the row in JSON, and return it back for use 
        	return json_encode($row_to_encode);
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * ends the application, displaying either nothing or the passed message as output if one exists 
 *
 * @access  public
 * @return  void  
 */

        public function graceful_end($exit_message = '')
        {
        	// end the application, displaying our exit message if we were passed one
        	die($exit_message);
        }
                
// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * attempts to match the passed GET request against a pre-defined list of allowed values
 *
 * @access  protected
 * @return  void 
 */

        protected function match_request()
        {
        	// check the request against each accepted brand in the array
        	foreach($this->accepted_brands as $row)
        	{		
        		// to improve readability of code, create two temp variables to hold the row and GET request as lowercase values
				$lcase_row_name	= strtolower($row['name']);
        		$lcase_get_req 	= strtolower($_GET['brand']);
        		
        		// check if we have a match within the name of each accepted brand
				if(strpos($lcase_row_name, $lcase_get_req) !== false) 
				{	
					// a match was found, let's encode it in JSON, then display the result
					$this->display_output($this->encode_row($row));
				}
        	}
        	
        	// a match was not found, exit gracefully
        	$this->graceful_end('null');
        }
        
// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Runs through the main methods in our application
 *
 * @access  public
 * @return  void
 */

        public function run()
        {
            // check to see if we have a GET request
            if(!$this->check_for_request())
            {
	            // we were not passed a request; exit gracefully
				$this->graceful_end('null');
            }
            
            // there is at least some data; let's attempt to match it
			$this->match_request();
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * sets our accepted brands from what we were passed during instantiation
 *
 * @access  protected
 * @return  void
 */
         
        protected function set_accepted_brands($passed_brands)
        {
			// set the protected accepted_brands array to what we were passed upon instantiation
			$this->accepted_brands	= $passed_brands;			
        }
    }
    
    

    // declare accepted brand array for use in matching and returning data
	$brands 	= array(	array('name'	=> 'Choice University',
								  'logo'	=> 'http://www.choiceuniversity.com/logo.png',
								  'url'		=> 'http://www.choiceuniversity.com'),
									  		
							array('name'	=> 'Engage University',
								  'logo'	=> 'http://www.engageuniversity.com/logo.jpg',
								  'url'		=> 'http://www.engageuniversity.com/engage'),
									  
							array('name'	=> 'Main University',
								  'logo'	=> 'http://www.mainuniversity.com/logo.gif',
								  'url'		=> 'http://www.mainuniversity.com/home'),
													
							array('name'	=> 'Supreme University',
								  'logo'	=> 'http://www.supremeuniversity.com/logo.png',
								  'url'		=> 'http://www.supremeuniversity.com/welcome'));

    // instantiate new brander object
    $app = new Brander($brands);

    // run the brander application
    $app->run();
    
    
