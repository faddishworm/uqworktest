# uqworktest

Thanks for taking the time to view my worktest project. The purpose of this readme explains some of the thought process and justification behind the frameworks/technologies used. 

## Framework & Library Choices
To implement the backend I have used the Slim Framework http://www.slimframework.com/

Its a great microframework for quick restful api implementations, it can be quickly installed using PHP composer and handles routing fairly well out of the box.

## Design and Architecture Consessions
As I am a bit pressed for time, I have used a file to persistently store any library added using the /api/library GET request.
I have written a class Library.php which houses the functionality converting from a JSON string, to an object, to a serialised
string so that storing and retrieving the data is straight forward.

The code is written quite defensively (checking for error statements first). This could be done more elegantly, using a generic
error handling model rather than hard coding each message as it is encountered.
