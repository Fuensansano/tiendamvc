<?php

enum MysqlReturnTypes: int
{
    case BOOLEAN = 0;
    case ONE = 1; // Fetch
    case ALL = 2; // All
    case COUNT = 3; // count
}
