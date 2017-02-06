<?php
use Hazzard\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testSetLines()
    {
        $translatorLines = [
            'required'             => 'testing the :attribute field translation is working!',
        ];
        $this->validator->setLines($translatorLines);
        $factory = $this->validator->getFactory();
        $validation = $factory->make([], [
            'test' => 'required'
        ]);
        self::assertTrue($validation->fails());
        self::assertEquals(
            [
                'test' => ['testing the test field translation is working!']
            ],
            $validation->getMessageBag()->toArray()
            );
    }

    public function testUsingDefaultTranslator()
    {

        $this->validator->setDefaultLines();
        $factory = $this->validator->getFactory();
        $validation = $factory->make(
            [],
            [
                'test' => 'required'
            ]
        );
        self::assertTrue($validation->fails());
        self::assertEquals(
            [
                'test' => ['The test field is required.']
            ],
            $validation->getMessageBag()->toArray()
        );
    }

    public function testSetGlobal()
    {
        $translatorLines = [
            'required' => ':argument doesn\'t exist and is required'
        ];
        $this->validator->setLines($translatorLines);
        $this->validator->setAsGlobal();

        $validation = Validator::make(
            [],
            [
                'test' => 'required'
            ]
        );
        self::assertTrue($validation->fails());
        self::assertEquals(
            [
                'test' => [':argument doesn\'t exist and is required']
            ],
            $validation->getMessageBag()->toArray()
        );
    }

    public function testCallMagicMethod()
    {

        $validation = $this->validator->make(
            [],
            [
                'test' => 'required'
            ]
        );

        self::assertTrue($validation->fails());
        self::assertEquals(
            [
                'test' => ['validation.required']
            ],
            $validation->getMessageBag()->toArray()
        );
    }
}