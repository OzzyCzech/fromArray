<?php

namespace DataLoader;

enum Types: string
{
    case Int = 'int';
    case Bool = 'bool';
    case String = 'string';
    case Float = 'float';
    case Array = 'array';
    case Mixed = 'mixed';
    case Object = 'object';
    case Enum = 'enum';
}

