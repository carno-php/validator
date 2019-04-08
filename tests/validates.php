<?php

return [
    ['valid-group', 'base-test'],
    ['valid', 'name', 'string|in:[NameC,NameD]'],
    ['valid-group'],
    ['valid-named', 'custom-fields', '* numeric|positive|min:10|max:100'],
];
