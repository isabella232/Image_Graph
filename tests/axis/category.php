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
 * This is a visual test case, testing the category axis.
 * 
 * @package Image_Graph
 * @subpackage Tests
 * @category images
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @version $Id$
 */

require 'Image/Graph.php';    

// create the graph
$Graph =& Image_Graph::factory('graph', array(400, 300));
// add a TrueType font
$Font =& $Graph->addNew('ttf_font', 'Gothic');
// set the font size to 11 pixels
$Font->setSize(7);

$Graph->setFont($Font);

// create the plotarea
$Graph->add(
    Image_Graph::vertical(
        Image_Graph::factory('title', array('Testing Category Axis', 10)),               
        $Plotarea = Image_Graph::factory('plotarea', array('Image_Graph_Axis_Category', 'Image_Graph_Axis_Category')),           
        5            
    )
);

$DS =& Image_Graph::factory('dataset');
$DS->addPoint('Germany', 'England');
$DS->addPoint('Denmark', 'France');
$DS->addPoint('Sweden', 'Denmark');
$DS->addPoint('England', 'France');
$DS->addPoint('Norway', 'Finland');
$DS->addPoint('Denmark', 'Finland');
$DS->addPoint('Iceland', 'Germany');
$DS->addPoint('Norway', 'France');

$DS2 =& Image_Graph::factory('dataset');
$DS2->addPoint('Sweden', 'France');
$DS2->addPoint('Austria', 'Germany');
$DS2->addPoint('Norway', 'Holland');
$DS2->addPoint('Denmark', 'Germany');
$DS2->addPoint('Sweden', 'Holland');
$DS2->addPoint('Iceland', 'Denmark');

/* Expect x-axis to be ordered:
 * Germany, Denmark, Sweden, England, Austria, Norway, Iceland
 * 
 * Expect y-axis to be ordered:
 * England, France, Denmark, Finland, Holland, Germany
 * 
 * Special points are X = Austria and Y = Holland, which are expected to be
 * "placed" before Norway and Germany respective (since that is the point at
 * which they exist "before" in the dataset on their first occurence)
 */ 

$Plot =& $Plotarea->addNew('line', &$DS);
$Plot->setLineColor('red');

$Plot2 =& $Plotarea->addNew('line', &$DS2);
$Plot2->setLineColor('blue');

$Graph->done();
?>