<?php


if (isset($tagName)) {
   echo "<$tagName>" . renderComponents($components) . "</$tagName>";
} else {
   echo renderComponents($components);
}
