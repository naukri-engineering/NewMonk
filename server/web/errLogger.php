<?php

/*
 * Usage: http://example.com/newmonk/server/errLogger.php?errorLogs={error logs in json format}
 *
 * Example Usage: http://example.com/newmonk/server/errLogger.php?errorLogs=<json_structure_as_described_below>
 *
 * ----------------------------------------
 * Complete json structure with all fields:
 * ----------------------------------------
{
    source: "app",                  [Possible Values: app/browser/server]
    appId: "1",                     [In case of app, possible values are: 1/2/3 (for India/NG/FN)]
    uId: "a3bdtf",                  [Optional. This is a unique identifier of the user/device.]

    environment: {
        os: {                       [app:Required. browser:Required. server:Optional]
            name: "iOS",
            version: "8"
        },
        browser: {                  [app:NotRequired. browser:Required. server:Optional]
            name: "Chrome",
            version: "43.0"
        },
        app: {                      [app:Required. browser:NotRequired. server:NotRequired]
            version: "2"
        },
        device: {                   [app:Required. browser:NotRequired. server:NotRequired]
            name: "iPhone 6"
        },
        display: {                  [app:NotRequired. browser:Required. server:NotRequired] //
            width: "1440",
            height: "900"
        }
    },

    exceptions: [                                       [Required]
        {
            tag: "srp",                                 [Required]
            count: 1,                                   [Optional. Default value: 1]
            timestamp: 1431994922,                      [app:Required. browser:NotRequired. server:Optional]
            type: "MathException",                      [Required]
            message: "Division by zero error",          [Optional]
            code: 5,                                    [Optional]
            file: "Example.java",                       [Optional]
            line: 23,                                   [Optional]
            stackTrace: "Exception in thread "main"\    [app:Required. browser:Optional. server:Required] //
java.lang.NullPointerException\
at com.example.myproject.Book.getTitle(Book.java:16)\
at com.example.myproject.Author.getBookTitles(Author.java:25)""
        }
    }
}
 */

namespace NewMonk\web;

require_once __DIR__.'/../config/config.php';

use NewMonk\lib\error\logger\Factory;

header('HTTP/1.0 204 No Content');
$errorLogs = isset($_REQUEST['errorLogs']) ? $_REQUEST['errorLogs'] : file_get_contents('php://input');
$errorLogger = Factory::getInstance()->getErrorLogger('queue');
$errorLogger->saveLogs($errorLogs);
