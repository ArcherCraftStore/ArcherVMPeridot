// ActionScript Documentimport fl.motion.Animator;
var lettermotione_xml:XML = <Motion duration="60" xmlns="fl.motion.*" xmlns:geom="flash.geom.*" xmlns:filters="flash.filters.*">
	<source>
		<Source frameRate="24" x="164" y="109.25" scaleX="0.21" scaleY="0.192" rotation="0" elementType="graphic" symbolName="e">
			<dimensions>
				<geom:Rectangle left="-167" top="-190.05" width="243" height="203.05"/>
			</dimensions>
			<transformationPoint>
				<geom:Point x="0.5002057613168724" y="0.500369367150948"/>
			</transformationPoint>
		</Source>
	</source>

	<Keyframe index="0" tweenSnap="true">
		<tweens>
			<SimpleEase ease="0"/>
		</tweens>
	</Keyframe>

	<Keyframe index="59" tweenSnap="true" x="31" y="-84">
		<tweens>
			<SimpleEase ease="0"/>
		</tweens>
	</Keyframe>
</Motion>;

var lettermotione_animator:Animator = new Animator(lettermotione_xml, lettermotione);
lettermotione_animator.play();
