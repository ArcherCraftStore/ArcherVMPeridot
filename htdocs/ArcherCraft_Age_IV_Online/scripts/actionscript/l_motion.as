// ActionScript Document
import fl.motion.Animator;
var l_motion_xml:XML = <Motion duration="66" xmlns="fl.motion.*" xmlns:geom="flash.geom.*" xmlns:filters="flash.filters.*">
	<source>
		<Source frameRate="24" x="322.5" y="33.65" scaleX="0.21" scaleY="0.148" rotation="0" elementType="graphic" symbolName="d">
			<dimensions>
				<geom:Rectangle left="70" top="-326" width="119" height="375.85"/>
			</dimensions>
			<transformationPoint>
				<geom:Point x="0.499579831932773" y="0.5001995476918983"/>
			</transformationPoint>
		</Source>
	</source>

	<Keyframe index="0" tweenSnap="true">
		<tweens>
			<SimpleEase ease="0"/>
		</tweens>
	</Keyframe>

	<Keyframe index="65" x="-102" y="67.19999999999999"/>
</Motion>;

var l_motion_animator:Animator = new Animator(l_motion_xml, l_motion);
l_motion_animator.play();
