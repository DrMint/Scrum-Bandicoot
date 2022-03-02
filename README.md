# Scrum Bandicoot

## Technical Documentation

To setup and run Scrum Bandicoot, follow the following steps:
1. Clone the master branch on the repository:
	```bash
	git clone https://github.com/DrMint/Scrum-Bandicoot.git
	```
2. Go inside the clone folder:
	```bash
	cd Scrum-Bandicoot
	```
3. Initialize the database:
	```bash
	cp db_empty.json db.json
	```
	Alternatively, you can also use the demo database used for the rest of this documentation and for the TESTING.md document:
	```bash
	cp db_example.json db.json
	```
4. Lastly, you can run the PHP server by using the following command (using port 3001):
	```bash
	php -S localhost:3001
	```
	
	
```ad-info

If you want to use the demo database, the default account is named `demo`, the password is also `demo`.

```


## User Documentation
### Login Page
On the Login Page, the user is asked to enter their **username** and **password** and click **Sign In**.
If the credentials are correct, the user is redirected the their [[Documentation#Profile Page|Profile Page]].
![](/doc/Screen Shot 2021-12-22 at 17.03.18.png)

If the entered **username** or **password** is wrong, the following alert appears:
![](/doc/Screen Shot 2021-12-22 at 17.31.52.png)

```ad-attention
title: Limitation

There is currently no way for the users to reset their passwords by themselves as they are not required to provide an email address on during their account creation. 

```


Lastly, from this page, the user is also able to click the **Create an account** to be redirected to the [[Documentation#Register Page|Register Page]].

### Register Page

One the Register Page, the user is asked to provide a **username** and **password** and click **Create account**. 
If the credentials are correct, the user is redirected the their [[Documentation#Profile Page|Profile Page]].
![](/doc/Screen Shot 2021-12-22 at 17.03.38.png)

If the entered **username** is already register, they are asked to try another name:
![](/doc/Screen Shot 2021-12-22 at 17.04.16.png)

### Profile Page

On their Profile Page, users are greeted with the following elements:
- At the top, the navbar displays the app title **Scrum Bandicoot**, a welcome message, and a logout button.
	- The **Scrum Bandicoot** title can be clicked to bring the user back to their [[Documentation#Profile Page|Profile Page]].
	- The **Logout** button logout the user and redirect them to the [[Documentation#Login Page|Login Page]].

- Bellow the navbar, the project for which the user is a member are listed under the title **My Projects**. The projects are displayed as cards with the project thumbnail displayed at the top, and the title of the project at the bottom. Next to **My Projects**, the user can click on **Create a project** to open the [[Documentation#Project Creation Form|Project Creation Form]]. 

- And lastly, there is a list of **Public projects**, which are projects the user haven't joined yet. The projects are also displayed as cards, however, a **join** button is present at the bottom of the card to allow the user to join the project. If a user join a project, the user will stay on the page and the joined project will now be moved from the **Public projects** section to the **My Projects** section.

![](/doc/Screen Shot 2021-12-22 at 17.04.31.png)

#### Project Creation Form
Projects can be easilly created using this form. The user simply needs to input the project's name.

![](/doc/Screen Shot 2021-12-22 at 17.04.43.png)

```ad-attention
title: Limitation

The projects' name and usernames are converted into slug when processed by the system. A slug is a string that can be safely included in the URL. Slugs are lowercase strings that only includes a to z characters, 0-9 digits, and the dash symbol "-" to encode spaces.

```

### Project Page

On a project page, the user can see the following elements:
- The title of the project is displayed at the top
- There is the **Leave project** button next to the project's title that allows the user to leave a project.
- The **My Sprints** section lists the past, current, and future sprints. It also includes the [[Documentation#Backlog Product|Backlog Product]].
	- The Backlog Product is a list of Tasks that hasn't been included in a Sprint yet. In the Scrum project management approach, the list of tasks that constitute the Backlogs Products is supposed to be compiled and ordered by priority before starting the project.
	- Past sprints will display when the sprint ended in a human-readable format (i.e., "Ended 20 days ago", "Ended 2 hours ago"...)
	- Current sprints will display when the sprint will end (i.e., "Ends in 3 days").
	- Future sprints will display when the sprint will start (i.e., "Starts in 4 days").
	- Next to the **My Sprints** title, there is a **Manage sprints** button that leads to the [[Documentation#Sprints Management|Sprints Management]] page.
- Lastly, there is the **My Tasks** section where the user can quickly see all the tasks he has been assigneed to. The user can click on the task to be redirected to the specific Sprint the task is located (or the Backlog Product if the task isn't yet part of a sprint).

![](/doc/Screen Shot 2021-12-22 at 17.04.58.png)

```ad-attention
title: Limitation

Currently, all tasks assigned to the user are displayed under My Tasks, including Tasks considered completed. It would be better to only display the tasks that are from the currently on-going sprint.
```

### Backlog Product

The Backlog Product is a very simplified Kanban board with just one column. The user can add, edit, remove tasks easilly from this interface. Please check the [[Documentation#Kanban Board|Kanban Board]] chapter to learn about how to interact with a Kanban board. 

![](/doc/Screen Shot 2021-12-22 at 17.27.24.png)

### Sprints Management

On the Sprints Management page, the user can see the following elements:
- The title of the project displayed at the top
- The **Return to project** button that redirect the user to the appropriate [[Documentation#Project Page|Project Page]].
- The list of past, current, and future sprints displayed as cards.

Those sprint cards display more information than on the [[Documentation#Project Page|Project Page]]:
- The date when the sprint starts and ends (including a human readable version).
- The cards also includes two buttons: the **Edit** and **Cancel** buttons.

![](/doc/Screen Shot 2021-12-22 at 17.26.17.png)

#### Sprint Edit Form

If the user clicks on one of the **Edit** buttons on the sprint cards, a form appears. On this form, the user can modify when the sprint starts and ends. Those dates must be today or further in the future.

![](/doc/Screen Shot 2021-12-22 at 17.26.34.png)

```ad-attention
title: Limitation
Currently, it is possible to edit Sprints even after their deadline. Which mean they can be re-enabled. This is a questionable behavior that shouldn't be permitted.
```

#### Sprint Creation
If the user clicks on the **Add Sprint** button, the following form appears.
The user is prompted for the following information:
- The sprint starting date
- The sprint ending date
- A subset of the [[Documentation#Backlog Product|Backlog Product]]'s tasks.

```ad-info
title: Info
You can select multiple tasks using the Ctrl key on the keyboard. Simply press the Ctrl key and then click on as many tasks as you want to select. You can also deselect a task by pressing Ctrl and clicking on the task you would like to deselect.
```

If the user confirms the creation of the Sprint, the selected Backlog Product's tasks are transfered to the new sprint. They are no longer in the Backlog Product.

![](/doc/Screen Shot 2021-12-22 at 17.26.41.png)

#### Sprint Cancelling

If the user clicks on the **Cancel** button on one of the sprint cards, the sprint is cancelled. This means that all tasks in that sprint are transfered back to the  [[Documentation#Backlog Product|Backlog Product]].

### Sprint Kanban Board

The Kanban Board is an integral part of Scrum Bandicoot. On the Kanban Board page, the user is greeted with the following elements:
- The **Return to project** button that redirect the user to the appropriate [[Documentation#Project Page|Project Page]].
- The **Create column** button that leads to the [[Documentation#Column Creation Form|Column Creation Form]]
- The Kanban Board
	- Columns are listed horizontally and seperated by a thin vertical line. Their title is displayed at the top in bold.
	- Tasks are listed vertically in their corresponding column. The task's title is displayed at the top of its card, and the assignees are listed in pill-shaped containers.
	- Within a column, bellow all its tasks, is located the **Create task** button that opens the [[Documentation#Task Creation Form|Task Creation Form]] 

![](/doc/Screen Shot 2021-12-22 at 17.05.25.png)


#### Option Menu
By hovering over a column's title, the option menu appears at the top of its title card.
![](/doc/Screen Shot 2021-12-22 at 17.06.05.png)

By hovering over a task's card, the option menu appears at its top.
![](/doc/Screen Shot 2021-12-22 at 17.06.17.png)

The option menu offers four functionalities for tasks:
- To **View**
- To **Edit**
- To **Delete** 
- To **Move** using the left and right arrows.

For columns, the **View** option isn't visible because there is no need for such function.

```ad-info

The option menu doesn't show up for the **Backlog Sprint** and **Done** columns because those two are locked: they cannot be moved, nor edited, nor deleted. The **Backlog Sprint** column is necessary because there is where the tasks are placed when a sprint is created. The **Done** column is also locked because it is needed to know when to consider a task to be done.

```


```ad-attention
title: Limitation

It was initially planned to be able to drag and drop tasks to another column instead of using button arrows. We had ideas on how to do detect a drag and how to send the message to the server. However you got stuck at the part where we need to display a floating version of the task that follows the cursor's movement. Due to time contraints we decide to leave it in a working state with those buttons, instead of releasing Scrum Bandicoot V1 with a broken drag and drop system.

```



#### Column Creation Form

![](/doc/Screen Shot 2021-12-22 at 17.12.06.png)

#### Column Edition Form

![](/doc/Screen Shot 2021-12-22 at 17.12.22.png)

#### Task Creation Form

![](/doc/Screen Shot 2021-12-22 at 17.11.53.png)

#### Task Edition Form

As with the multi selection menu for the [[Documentation#Sprint Creation|Sprint Creation]] form, you can select multiple members by pressing Ctrl while clicking on a member. You can also deselect a member by pressing the Ctrl key and clicking on an already selected member.

![](/doc/Screen Shot 2021-12-22 at 21.44.13.png)

#### Task View Popup

![](/doc/Screen Shot 2021-12-22 at 17.07.32.png)
