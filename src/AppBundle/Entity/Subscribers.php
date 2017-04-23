<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listing
 */
class Subscribers{

    const FILE = 'list.json';

    protected $errors;

    /**
     * @Assert\DateTime()
     */
    public $createdAt;


    public $id;

    /**
     * @Assert\NotBlank(
     * message = "Field Name should not be blank "
     * )
     */
    public $name;

    /**
     * @Assert\NotBlank(
     * message = "Field Email address should not be blank "
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank(
     * message = "Field Categories should not be blank "
     * )
     */
    public $categories;


    public function setName($name)
    {
        $this->name = $name;
    }

    public  function setId($id){

        $this->id = $id;

    }

    public function getName()
    {
        return $this->name;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
    public function getErrors()
    {
        return $this->errors;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }


    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
    public function getCategories()
    {
        return $this->categories;
    }


    public function getSelection(){

        $out = [
            'Auto' => 'auto',
            'Food' => 'food',
            'Entertainment' => 'entertainment',
            'World news' => 'world news',
            'Lithuania' => 'lithuania',
        ];

        return $out;

    }

    public function saveData($data, $kernel){

        $file = $kernel->getRootDir().'/filex/people.txt';
        $current = file_get_contents($file);
        $current .= "John Smith\n";
        file_put_contents($file, $current);

        return true;
    }


}