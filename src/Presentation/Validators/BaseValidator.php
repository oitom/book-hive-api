<?php

namespace App\Presentation\Validators;

abstract class BaseValidator
{
  protected array $rules;

  public function __construct(array $rules)
  {
    $this->rules = $rules;
  }

  public function validate(array $data): array
  {
    $errors = [];

    foreach ($this->rules as $field => $rule) {
      [$required, $maxLength, $type] = $rule;

      // Verifica se o campo é obrigatório
      if ($required === 'required' && empty($data[$field])) {
        $errors[$field] = "$field é obrigatório.";
        continue;
      }

      // Verifica o tipo do campo
      if (!empty($data[$field])) {
        switch ($type) {
          case 'string':
            if (!is_string($data[$field]) || strlen($data[$field]) > $maxLength) {
              $errors[$field] = "$field deve ser uma string com no máximo $maxLength caracteres.";
            }
            break;

          case 'integer':
            if (!is_int($data[$field])) {
              $errors[$field] = "$field deve ser um número inteiro.";
            }
            break;

          case 'decimal':
            if (!is_numeric($data[$field])) {
              $errors[$field] = "$field deve ser um número decimal.";
            }
            break;

          case 'array':
            if (!is_array($data[$field]) || count($data[$field]) < $maxLength) {
              $errors[$field] = "$field deve ser um array com pelo menos $maxLength item(s).";
            }
            break;

          default:
            $errors[$field] = "Tipo inválido para o campo $field.";
        }
      }
    }

    return $errors;
  }
}
