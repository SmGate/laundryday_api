<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64ImageOrSvg implements ValidationRule
{
  protected $maxSize;
    protected $allowedTypes;
 public function validate($attribute, $value, array $parameters, $validator)
    {
        return $this->passes($attribute, $value);
    }
    public function __construct($maxSize = null, $allowedTypes = ['jpeg', 'jpg', 'png', 'gif', 'svg+xml'])
    {
        $this->maxSize = $maxSize;
        $this->allowedTypes = $allowedTypes;
    }

    public function passes($attribute, $value)
    {
        // Check if value is a valid base64 string
        if (!preg_match('/^data:image\/(\w+);base64,/', $value, $type)) {
            return false;
        }

        // Extract image type
        $type = strtolower($type[1]);

        // Check if image type is allowed
        if (!in_array($type, $this->allowedTypes)) {
            return false;
        }

        // Remove the base64 header to get the raw base64 string
        $base64Str = substr($value, strpos($value, ',') + 1);

        // Decode the base64 string
        $decoded = base64_decode($base64Str, true);

        // Check if decoding was successful
        if (!$decoded) {
            return false;
        }

        // Check if the decoded content is a valid image
        if (in_array($type, ['jpeg', 'jpg', 'png', 'gif'])) {
            $image = @imagecreatefromstring($decoded);
            if (!$image) {
                return false;
            }
        } elseif ($type === 'svg+xml') {
            // Validate SVG format
            libxml_disable_entity_loader(true);
            $svgDoc = simplexml_load_string($decoded);
            if (!$svgDoc) {
                return false;
            }
        }

        // Check image size if maxSize is set
        if ($this->maxSize && (strlen($decoded) > $this->maxSize)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute field must be a valid base64 encoded image or SVG.';
    }
    
}
