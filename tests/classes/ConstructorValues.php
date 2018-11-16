<?php declare(strict_types = 1);

namespace WebChemistry\Test;

final class ConstructorValues {

	public function __construct(string $string, ?string $nullable, $mixed, $default = 'foo') {
	}

}
