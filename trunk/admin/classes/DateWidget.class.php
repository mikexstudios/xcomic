<?php
/**
Xcomic

$Id$
*/

/** Helper class to make all date processing the same.
*/
class DateWidget
{
    var $timestamp;
    var $_forminfo;

    function DateWidget($time = null)
    {
        $this->timestamp = $time ? $time : time();
        
        $this->_forminfo['month'] = 'datemonth';
        $this->_forminfo['day'] = 'dateday';
        $this->_forminfo['year'] = 'dateyear';
        $this->_forminfo['hour'] = 'datehour';
        $this->_forminfo['minute'] = 'dateminute';
        $this->_forminfo['second'] = 'datesecond';
    }
    
    function getTime()
    {
        return $this->timestamp;
    }

    /** Display the date widget.
    */
    function printWidget($title = "Date:")
    {
        $date['month'] = date('n', $this->timestamp);
        $date['day'] = date('d', $this->timestamp);
        $date['year'] = date('Y', $this->timestamp);
        $date['hour'] = date('H', $this->timestamp);
        $date['minute'] = date('i', $this->timestamp);
        $date['second'] = date('s', $this->timestamp);
        ?>
        <fieldset class="date"><legend><?php echo $title; ?></legend>
          <select name="<?php echo $this->_forminfo['month']; ?>">
            <option value="1"<?php echo $date['month'] == 1 ? ' selected="selected"' : '';?>>January</option>
            <option value="2"<?php echo $date['month'] == 2 ? ' selected="selected"' : '';?>>February</option>
            <option value="3"<?php echo $date['month'] == 3 ? ' selected="selected"' : '';?>>March</option>
            <option value="4"<?php echo $date['month'] == 4 ? ' selected="selected"' : '';?>>April</option>
            <option value="5"<?php echo $date['month'] == 5 ? ' selected="selected"' : '';?>>May</option>
            <option value="6"<?php echo $date['month'] == 6 ? ' selected="selected"' : '';?>>June</option>
            <option value="7"<?php echo $date['month'] == 7 ? ' selected="selected"' : '';?>>July</option>
            <option value="8"<?php echo $date['month'] == 8 ? ' selected="selected"' : '';?>>August</option>
            <option value="9"<?php echo $date['month'] == 9 ? ' selected="selected"' : '';?>>September</option>
            <option value="10"<?php echo $date['month'] == 10 ? ' selected="selected"' : '';?>>October</option>
            <option value="11"<?php echo $date['month'] == 11 ? ' selected="selected"' : '';?>>November</option>
            <option value="12"<?php echo $date['month'] == 12 ? ' selected="selected"' : '';?>>December</option>
          </select>
          <input type="text" name="<?php echo $this->_forminfo['day']; ?>" size="3" maxlength="2" value="<?php echo $date['day']; ?>" />,
          <input type="text" name="<?php echo $this->_forminfo['year']; ?>" size="5" maxlength="4" value="<?php echo $date['year']; ?>" />
          &nbsp;&nbsp;
          <input type="text" name="<?php echo $this->_forminfo['hour']; ?>" size="3" maxlength="2" value="<?php echo $date['hour']; ?>" /> :
          <input type="text" name="<?php echo $this->_forminfo['minute']; ?>" size="3" maxlength="2" value="<?php echo $date['minute']; ?>" />
          
          <input type="hidden" name="<?php echo $this->_forminfo['second']; ?>" value="<?php echo $date['second']; ?>" />
		</fieldset>
        <?php
    }
    
    /** Process the date input from a form.
    */
    function processWidget()
    {
        $comicMonth = intval($_REQUEST[$this->_forminfo['month']]);
	    $comicDay = intval($_REQUEST[$this->_forminfo['day']]);
	    $comicYear = intval($_REQUEST[$this->_forminfo['year']]);
	    $comicHour = intval($_REQUEST[$this->_forminfo['hour']]);
	    $comicMinute = intval($_REQUEST[$this->_forminfo['minute']]);
	    $comicSecond = intval($_REQUEST[$this->_forminfo['second']]);
	    $this->timestamp = mktime($comicHour, $comicMinute, $comicSecond, $comicMonth, $comicDay, $comicYear);
    }
}
?>
