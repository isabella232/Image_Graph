<?php
// +--------------------------------------------------------------------------+
// | Image_Graph                                                              |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2003, 2004 Jesper Veggerby                                 |
// | Email         pear.nosey@veggerby.dk                                     |
// | Web           http://pear.veggerby.dk                                    |
// | PEAR          http://pear.php.net/package/Image_Graph                    |
// +--------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU Lesser General Public               |
// | License as published by the Free Software Foundation; either             |
// | version 2.1 of the License, or (at your option) any later version.       |
// |                                                                          |
// | This library is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU        |
// | Lesser General Public License for more details.                          |
// |                                                                          |
// | You should have received a copy of the GNU Lesser General Public         |
// | License along with this library; if not, write to the Free Software      |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA |
// +--------------------------------------------------------------------------+

/**
 * Image_Graph - PEAR PHP OO Graph Rendering Utility.
 * @package Image_Graph
 * @subpackage Plot     
 * @category images
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @version $Id$
 */ 

/**
 * Include file Image/Graph/Plot/Bar.php
 */
require_once 'Image/Graph/Plot/Bar.php';

/**
 * Stepchart
 */
class Image_Graph_Plot_Step extends Image_Graph_Plot
{

    /**
     * PlotType [Constructor]
     * A 'normal' step chart is 'stacked'     
     * @param Dataset $dataset The data set (value containter) to plot
     * @param string $multiType The type of the plot
     * @param string $title The title of the plot (used for legends, {@see Image_Graph_Legend})
     */
    function &Image_Graph_Plot_Step(& $dataset, $multiType = 'stacked', $title = '')
    {
        $multiType = strtolower($multiType);
        if (($multiType != 'stacked') and ($multiType != 'stacked100pct')) {
            $multiType = 'stacked';
        }          
        parent::Image_Graph_Plot($dataset, $multiType, $title);
    }
        
    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        Image_Graph_Plot::_done();

        if ($this->_multiType == 'stacked100pct') {
            $total = $this->_getTotals();
        }

        $width = $this->width() / ($this->_maximumX() + 2) / 2;

        reset($this->_dataset);
        $keys = array_keys($this->_dataset);

        list ($ID, $key) = each($keys);
        $dataset = & $this->_dataset[$key];

        $point = array ('X' => $dataset->minimumX(), 'Y' => 0);
        $base[] = $this->_pointY($point);
        $first = $this->_pointX($point) - $width;
        $base[] = $first;

        $point = array ('X' => $dataset->maximumX(), 'Y' => 0);
        $base[] = $this->_pointY($point);
        $base[] = $this->_pointX($point) + $width;                
        reset($keys);
        while (list ($ID, $key) = each($keys)) {
            $dataset = & $this->_dataset[$key];
            $dataset->_reset();
            $polygon = array_reverse($base);
            unset ($base);
            $last = $first;
            while ($point = $dataset->_next()) {
                $x = $point['X'];
                $p = $point;
                
                if (!isset($current[$x])) {
                    $current[$x] = 0;
                }
                                        
                if ($this->_multiType == 'stacked100pct') {                            
                    $p['Y'] = 100 * ($current[$x] + $point['Y']) / $total['TOTAL_Y'][$x];
                } else {
                    $p['Y'] += $current[$x];
                }
                $current[$x] += $point['Y'];
                $point = $p;
                                    
                $x0 = $last;
                $y0 = $this->_pointY($point);
                $last = $x1 = $this->_pointX($point) + $width;
                $y1 = $this->_pointY($point);
                $polygon[] = $x0; $base[] = $y0;
                $polygon[] = $y0; $base[] = $x0;
                $polygon[] = $x1; $base[] = $y1;
                $polygon[] = $y1; $base[] = $x1;
            }
        
            ImageFilledPolygon($this->_canvas(), $polygon, count($polygon)/2, $this->_getFillStyle());
            ImagePolygon($this->_canvas(), $polygon, count($polygon)/2, $this->_getLineStyle());
        }
        $this->_drawMarker();
    }
}

?>