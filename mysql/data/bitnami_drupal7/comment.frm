�
    @  &�	          	      !        &  h�  '        //  0                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ^       �         �    	�] 
  ) 
   �
    	�] 
  �U   �    
�^  @�     �    ) (    �
    ��	   $      �U   �PRIMARY�comment_status_pid�comment_num_new�comment_uid�comment_nid_language�comment_created�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       �                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       InnoDB                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         p                                           $Stores comments and associated data.                                                                                                                                                                               � 
  �	]         P   P  � )                                          cid  pid  nid  uid  subject 	 	hostname 
 created  changed  status  thread  name  mail  	homepage  	language               r  
      .        c G�        ! 		F��       ! 
 U     ;  Y     ?  ] 
    E H�^  @   !@ J� ]  �   !| J�   �   !� 	F��  �   !� 	$$ �	      !' �cid�pid�nid�uid�subject�hostname�created�changed�status�thread�name�mail�homepage�language� Primary Key: Unique comment ID.The comment.cid to which this comment is a reply. If set to 0, this comment is not a reply to an existing comment.The node.nid to which this comment is a reply.The users.uid who authored the comment. If set to 0, this comment was created by an anonymous user.The comment title.The author’s host name.The time that the comment was created, as a Unix timestamp.The time that the comment was last edited, as a Unix timestamp.The published status of a comment. (0 = Not Published, 1 = Published)The vancode representation of the comment’s place in a thread.The comment author’s name. Uses users.name if the user is logged in, otherwise uses the value typed into the comment form.The comment author’s e-mail address from the comment form, if user is anonymous, and the ’Anonymous users may/must leave their contact information’ setting is turned on.The comment author’s home page address from the comment form, if user is anonymous, and the ’Anonymous users may/must leave their contact information’ setting is turned on.The languages.language of this comment.