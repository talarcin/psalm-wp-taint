<?php

function testFunctionName(): void
{
  if (true) {
    print_r("This is fun!");
  }
}

function testFunctionNameTwo(bool $doIt): string
{
  $doItTwice = $doIt && 1 == 1 ? "yes" : "no";
  return $doItTwice;
}
class Test
{
  public function testFunctionNameThree(): void
  {
    switch ("yes") {
      case 'no':
        print_r("ok, fine");
        break;

      default:
        print_r("not fine");
        break;
    }
  }
}
