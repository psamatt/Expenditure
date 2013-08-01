<?php

namespace Expenditure\Controller;

use Carbon\Carbon;

class BaseController
{
    /**
     * Instance of Spot
     *
     * @access protected
     * @var \Spot\Mapper
     */
    protected $db;
    
    /**
     * Twig
     *
     * @access protected
     * @var Twig
     */
    protected $twig;

    public function setDB(\Spot\Mapper $db)
    {
        $this->db = $db;
    }
    
    /**
     * Set the twig renderer
     *
     * @param TwigRenderer $twig
     */
    public function setTwigRenderer($twig)
    {
        $this->twig = $twig;
    }
    
    /**
     * Get the carbon object
     * 
     * @return Carbon
     */
    public function getCarbon($date = null)
    {
        return new Carbon($date);
    }
}