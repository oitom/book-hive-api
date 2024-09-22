<?

namespace Tests\Presentation\Validators;

use App\Presentation\Validators\BaseValidator;
use PHPUnit\Framework\TestCase;

class BookValidator extends BaseValidator
{
    public function __construct()
    {
        // Define as regras para os campos que deseja validar
        $rules = [
            'title' => ['required', 255, 'string'],
            'author' => ['required', 100, 'string'],
            'publishedYear' => ['required', null, 'integer'],
            'price' => ['required', null, 'decimal'],
            'tags' => ['optional', 2, 'array'],
        ];

        parent::__construct($rules);
    }
}
class BaseValidatorTest extends TestCase
{
    protected BookValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BookValidator();
    }

    public function testValidateRequiredFields()
    {
        $data = [
            'title' => '',
            'author' => '',
            'publishedYear' => '',
            'price' => ''
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('author', $errors);
        $this->assertArrayHasKey('publishedYear', $errors);
        $this->assertArrayHasKey('price', $errors);
    }

    public function testValidateStringLength()
    {
        $data = [
            'title' => str_repeat('A', 256), // mais que 255 caracteres
            'author' => str_repeat('B', 101) // mais que 100 caracteres
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('author', $errors);
    }

    public function testValidateIntegerField()
    {
        $data = [
            'publishedYear' => 'not-an-integer',
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('publishedYear', $errors);
    }

    public function testValidateDecimalField()
    {
        $data = [
            'price' => 'not-a-decimal',
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('price', $errors);
    }

    public function testValidateArrayField()
    {
        $data = [
            'tags' => 'not-an-array',
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('tags', $errors);
    }

    public function testValidateSuccessful()
    {
        $data = [
            'title' => 'A Great Book',
            'author' => 'John Doe',
            'publishedYear' => 2021,
            'price' => 19.99,
            'tags' => ['fiction', 'adventure']
        ];

        $errors = $this->validator->validate($data);

        $this->assertEmpty($errors);
    }
}