/**
 * A simple dice roller CLI PHP application that accepts two parameters: the number of dice (1-1000) and the number of sides (2-100)
 *
 * The code attempts to handle and sanitize bad user input gracefully.
 * 
 * The string argument is in the following format: {digit}d{digit} 
 * The first digit represents the number of dice and the second digit represents the number of sides. 
 *
 *
 * CLI example rolling 3 dice that have 6 sides each:   
 * bash: php roll.php 3d6
 *
 * returns: 
 * bash: 18
 *
 *
 *
 */
 
<?php namespace Leahy\John;

    class Diceroller extends Printer
    {
        // declare arrays for use
        protected $passed_args = array();

        // declare variables for use
        protected $min_dice_num = 1;
        protected $max_dice_num = 1000;
        protected $min_sides_num = 2;
        protected $max_sides_num = 100;
        protected $number_of_dice;
        protected $number_of_sides;
        protected $roll_result;

        public function __construct($argv)
        {
            // call method to save the cli arguments that were passed to us
            $this->set_passed_args($argv);
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Check the number of passed arguments
 *
 * @access  public
 * @return  bool        returns true if error check passes
 */

        public function check_arg_count()
        {
            // begin checking the number of arguments the user entered
            if(sizeof($this->passed_args) === 1)
            {
                // user failed to enter an argument; throw an error
                $this->throw_error(1);           // error: no arguments
            }
            elseif(sizeof($this->passed_args) >= 3)
            {
                // user entered more than one argument; throw an error
                $this->throw_error(2);           // error: too many arguments
            }

            // argument check passed
            return TRUE;
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Check the arguments for validity
 *
 * @access  public
 * @return  void
 */
        public function check_arg_validity()
        {
            // grab the actual arguments from the array
            $passed_value   = $this->passed_args[1];


            // convert arguments to lowercase values
            $passed_value   = strtolower($passed_value);

            // check to ensure
            if(! preg_match('~\d{1,4}\D\d{1,4}~', $passed_value))
            {
                // check to ensure the user entered a 'd'
                if(! strpos($passed_value, 'd'))
                {
                    // user did not enter a 'd'; throw an error
                    $this->throw_error(3);           // error: no 'd' entered
                }

                // check to ensure the user entered the number of dice
                if(substr($passed_value, 0, strlen($passed_value)) === 'd')
                {
                    // user did not enter the number of dice; throw an error
                    $this->throw_error(6);           // error: no number of dice
                }

                // check to ensure the user entered the number of sides
                if(substr($passed_value, 0, strlen($passed_value)) === 'd')
                {
                    // user did not enter the number of dice; throw an error
                    $this->throw_error(7);           // error: no number of sides
                }
            }

            // arguments are valid; split them
            $this->split_args($passed_value);
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Check the number of dice and number of sides for validity
 *
 * @access  public
 * @return  void
 */

        public function check_dice_validity()
        {
            // ensure user entered a numerical values for both number of dice
            if(! is_numeric($this->number_of_dice))
            {
                // user did not enter a numerical value; throw an error
                $this->throw_error(4);           // error: number of dice is not a number
            }

            // ensure user entered a numerical values for both number of sides
            if(! is_numeric($this->number_of_sides))
            {
                // user did not enter a numerical value; throw an error
                $this->throw_error(5);           // error: number of sides is not a number
            }

            // ensure user entered a valid number of dice
            if($this->number_of_dice < $this->min_dice_num || $this->number_of_dice > $this->max_dice_num)
            {
                // user entered an invalid number; throw an error
                $this->throw_error(8);           // error: invalid number of dice
            }

            // ensure user entered a valid number of sides
            if($this->number_of_sides < $this->min_sides_num || $this->number_of_sides > $this->max_sides_num)
            {
                // user entered an invalid number; throw an error
                $this->throw_error(9);           // error: invalid number of sides
            }
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Generate a random result based on the lowest and highest possible values
 *
 * @access  public
 * @return  void
 */

        public function do_dice_roll()
        {
            // lowest possible value is 1 * the number of dice
            $lowest_pos_value           = $this->number_of_dice;

            // highest possible value is the number of dice multiplied by the number of sides
            $highest_pos_value          = $this->number_of_dice * $this->number_of_sides;

            // generate our random result from the number of dice and number of sides each has
            $this->roll_result           = mt_rand($lowest_pos_value, $highest_pos_value);
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Runs through the main methods in the application
 *
 * @access  public
 * @return  void
 */

        public function run()
        {
            // check the number of arguments that were passed to us
            $this->check_arg_count();

            // check if the user entered the basic arguments correctly
            $this->check_arg_validity();

            // check if the user entered the number of dice correctly
            $this->check_dice_validity();

            // generate a random result
            $this->do_dice_roll();

            // return the results of our dice roll
            $this->print_roll_result();
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Sets the number of dice and number of sides from pieces of the passed argument
 *
 * @access  private
 * @param   string      $arg_piece_left     argument containing the number of dice entered
 * @param   strng       $arg_piece_right    argument containing the number of sides on each dice
 * @return  void
 */

        private function set_args($arg_piece_left, $arg_piece_right)
        {
            // set our variables from the individual pieces
            $this->number_of_dice        = $arg_piece_left;
            $this->number_of_sides       = $arg_piece_right;
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Sets the default configuration for properties used throughout the application
 *
 * @access  private
 * @param   array       $config   contains the filename and all other command line arguments
 * @return  void
 */
        public function set_config($config)
        {
            // set the properties for application variables
            $this->min_dice_num  = $config['min_dice_num'];
            $this->max_dice_num  = $config['max_dice_num'];
            $this->min_sides_num = $config['min_sides_num'];
            $this->max_sides_num = $config['max_sides_num'];
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Sets the passed_args variable to the command line arguments
 *
 * @access  private
 * @param   array       $args_to_get    contains the filename and all other command line arguments
 * @return  void
 */

        private function set_passed_args($args_to_set)
        {
            // set our variable to the arguments that were passed to us via the CLI
            $this->passed_args   = $args_to_set;
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Splits the arguments into two pieces
 *
 * @access  private
 * @params  string      $passed_value   containts two parameters separated by a lowercase d
 * @return  void
 */

        private function split_args($passed_value)
        {
            // decalare our array
            $arg_pieces     = array();


            // separate the arguments into two pieces, using lowercase 'd' as our delimiter
            $arg_pieces     = explode('d', $passed_value);


            // set our arguments from the pieces
            $this->set_args($arg_pieces[0], $arg_pieces[1]);
        }

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Throws a specific error based on the passed number then haults the application
 *
 * @access  protected
 * @param   integer     $error_num      the number of the specific error to throw
 * @return  void
 */
        final protected function throw_error($error_num)
        {
            // declare our variables
            $error;


            // check what exception number we were passed
            switch($error_num)
            {
                case 1:
                    // return error: no arguments
                    $error  = "Error: No value was entered!\n\n";
                    break;

                case 2:
                    // return error: too many arguments
                    $error  = "Error: Too many values were entered!\n\n";
                    break;

                case 3:
                    // return error: no 'd' in argument
                    $error  = "Error: Incorrect format submitted!\n\n";
                    break;

                case 4:
                    // return error: number of dice is invalid
                    $error  = "Error: Number of dice was not a valid number!\n\n";
                    break;

                case 5:
                    // return error: number of sides is invalid
                    $error  = "Error: Number of sides was not a valid number!\n\n";
                    break;

                case 6:
                    // return error: number of dice was not entered
                    $error  = "Error: Number of dice was not entered!\n\n";
                    break;

                case 7:
                    // return error: number of sides was not entered
                    $error  = "Error: Number of sides was not entered!\n\n";
                    break;

                case 8:
                    // return error: number of sides was not entered
                    $error  = "Error: Number of dice is invalid!\n\n";
                    break;

                case 9:
                    // return error: number of sides was not entered
                    $error  = "Error: Number of sides is invalid!\n\n";
                    break;

                default:
                    // default error message
                    $error  = "An error was encountered!\n\n";
                    break;
            }

            // append an example to our message
            $error = $error . "Please retry with the following format: {# of dice}d{# of dice sides}  ie: 1d6\n\n";

            // generate a new error
            echo $error;

            // hault; we encountered an error
            die();
        }
    }

    abstract class Printer
    {

// ----------------------------------------------------------------------------------------------------------------------------------------

/**
 * Displays the random result to the user on their screen
 *
 * @access  public
 * @return void
 */
        public function print_roll_result()
        {
            // append some meaningfull text along with the actual result
            $result_to_print    = $result_to_print . "\n\nYou rolled a: " . $this->roll_result . "\n\n";

            // output the result of the dice roll to the user
            echo $result_to_print;
        }
    }

    // instantiate new dicerroller object
    $app = new Diceroller($argv);

    // default configuration
    $config      = array('min_dice_num'   => 1,
                         'max_dice_num'   => 1000,
                         'min_sides_num'  => 2,
                         'max_sides_num'  => 100);

    // set the default configuration
    $app->set_config($config);

    // run the dice roller
    $app->run();
