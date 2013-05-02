elefunds PHP Client Library 1.2.1
=================================

Welcome to the elefunds PHP Client Library.

We put a lot of effort into making your daily work with our elefunds API an ease and loads of fun.
Therefore we tried to minimize the workload for shop implementers and developers as much as possible 
while offering you all the necessary freedoms to develop your idea just as quick and sustainable as possible.

We have been working hard on a finite guide to the API and libraries at hand, and there is already an introduction written in
`Documentation/PHPGuide.md`, as well as more general information in the root folder of this repository (`/Documentation`)!

There's also an excellent inline documentation as well as simple examples (check out the `/Examples`Folder)
and a clean and structured code base.

So please enjoy working with us and never hesitate to call or mail in case of problems, questions or the need for an immediate response.

Questions concerning bugtracking, feature requests or suggestions are always welcome!

For questions regarding the API or the PHP Client Library, please contact Christian Peters, <christian@elefunds.de>!


Prerequisites
-------------

This PHP Client Library requires PHP 5.3 or higher and should therefore work on every modern and even a bit aged system.
However, if you want to run the tests, you need PHP 5.3.8 or higher. The tests are mainly for code quality and are not
needed in a production environment.


What is it about?
-----------------

The API is basically about retrieving donation receivers (e.g. NGOs like Greenpeace, that can receive donations) and -
if a donation was made - sending back donation data to the API.

We have encapsulated the whole process as you can see in `Example/rawDataExample.php`, that handles with the pure
data that is returned from the API but already abstracts them in plain old php objects.

There is as well an example for advanced templating in `Example/shopExample.php`.


Summary
-------

We are sure that this is one small step to revolutionize the way we consume and 
how we take responsibility in our daily life decisions.
So please enjoy all the good deeds you can enable your customers to and help us change the world.
We're super excited to see your ideas coming to live with it.
After all, have fun with this API!
