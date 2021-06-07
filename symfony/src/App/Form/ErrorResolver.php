<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class ErrorResolver
{
    public static function getErrorsFromForm(FormInterface $form, bool $child = false): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            if ($child) {
                $errors[] = $error->getMessage();
            } else {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = self::getErrorsFromForm($childForm, true)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
