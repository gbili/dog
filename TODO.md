Because of changes made to the GbiliUserModule User entitiy,
every form that used to associate data to user should be changed
to userdata. That's because things are now associated with userdata.
For example: Categories, Posts, Dogs etc.

The user is redirected to profile editor, but it cannot be completed
because the default media is missing. The problem is that no media can
be added until the profile is completed. Thus there needs to be a way
to add images when there is no profile yet for the first user.
For example: removing the redirection to profile editor, add image, and
then reenable it.
