<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Register
 *
 * @author adrian
 *
 * 
 * @ORM\Table(name="registration")
 * @ORM\Entity()
 *
 */
class Register {

    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * 
     * @Assert\NotBlank
     * 
     * @Assert\Regex(
     *              pattern = "/^[a-zA-Z]+ [a-zA-Z]+$/",
     *              message = "Musisz podać imię i nazwisko"
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     * 
     * @Assert\NotBlank
     * 
     * @Assert\Email
     */
    private $email;
    
    /**
     *
     * @ORM\Column(name="sex", type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(name="birthdate", type="date")
     * 
     * @Assert\Date
     */
    private $birthday;

    /**
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     * 
     * @Assert\NotBlank
     */
    private $country;

    /**
     * @ORM\Column(name="course", type="string", length=255)
     * 
     * @Assert\NotBlank
     */
    private $course;

    /**
     * @ORM\Column(name="invest", type="array")
     * 
     * @Assert\NotBlank
     * 
     * @Assert\Count(
     *              min=2
     * )
     */
    private $invest;
    
    /**
     * @ORM\Column(type="string", length=65535, nullable=true)
     * 
     * @Assert\Length(
     *          max=3000
     * )
     */
    private $comments;

    /**
     * @Assert\NotBlank
     * 
     * @Assert\File(
     *              maxSize = "1M",
     *              mimeTypes = {"application/pdf", "application/x-pdf"},
     *              mimeTypesMessage = "Wymagany plik .PDF."
     * )
     * 
     */
    private $paymentfile;


    public function save($savePath) {
//        $formData=$form->getData();
//            unset($formData['paymentfile']);

        $paramsName = array('name', 'email', 'sex', 'birthday',
            'country', 'course', 'invest', 'comments');
        $formData = array();

        foreach ($paramsName as $name) {
            $formData[$name] = $this->{$name};
        }

        $randVal = rand(100, 999);
        $dataFileName = sprintf('data_%d.txt', $randVal);

        file_put_contents($savePath . $dataFileName, print_r($formData, TRUE));

//            $file = $form->get('paymentfile')->getData();
        $file = $this->getPaymentfile();

        if (NULL !== $file) {
            $newName = sprintf('file_%d.%s', $randVal, $file->guessExtension());
            $file->move($savePath, $newName);
        }
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Register
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Register
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Register
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Register
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Register
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set course
     *
     * @param string $course
     *
     * @return Register
     */
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set invest
     *
     * @param array $invest
     *
     * @return Register
     */
    public function setInvest($invest)
    {
        $this->invest = $invest;

        return $this;
    }

    /**
     * Get invest
     *
     * @return array
     */
    public function getInvest()
    {
        return $this->invest;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Register
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    public function getPaymentfile() {
        return $this->paymentfile;
    }

    public function setPaymentfile($paymentfile) {
        $this->paymentfile = $paymentfile;
        return $this;
    }


}
